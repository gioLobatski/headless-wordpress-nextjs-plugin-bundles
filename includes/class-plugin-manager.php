<?php
/**
 * Plugin Manager Class
 * Manages the bundled plugins installation and activation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Bundle_Plugin_Manager {
    
    /**
     * Installer instance
     */
    private $installer;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->installer = new WP_Bundle_Plugin_Installer();
    }
    
    /**
     * Get list of bundled plugins
     * Reads from the download configuration file
     * 
     * @return array
     */
    public function get_bundled_plugins() {
        $bundled_plugins = array();
        
        // Load plugin download configuration
        $config_file = WP_BUNDLE_PLUGIN_DIR . 'includes/plugin-download-config.php';
        
        if ( file_exists( $config_file ) ) {
            $plugin_config = include $config_file;
            
            foreach ( $plugin_config as $slug => $config ) {
                $bundled_plugins[] = array(
                    'path' => '', // Not used for remote downloads
                    'name' => $slug,
                    'type' => 'remote',
                    'zip_file' => $config['zip_file'],
                    'version' => $config['version'],
                    'download_url' => '' // Will be built by installer
                );
            }
        }
        
        return $bundled_plugins;
    }
    
    /**
     * Install all bundled plugins from GitHub Releases
     * 
     * @return array
     */
    public function install_bundled_plugins() {
        $bundled_plugins = $this->get_bundled_plugins();
        $results = array();
        
        foreach ( $bundled_plugins as $plugin ) {
            // Use GitHub download method
            $result = $this->installer->install_from_github( 
                $plugin['name'], 
                $plugin['zip_file'], 
                $plugin['version'] 
            );
            
            if ( $result['success'] ) {
                // Activate the plugin
                if ( ! empty( $result['plugin_slug'] ) ) {
                    $activation_result = $this->installer->activate_plugin( $result['plugin_slug'] );
                    $result['activation'] = $activation_result;
                }
            }
            
            $results[] = $result;
        }
        
        // Store installation results
        update_option( 'wp_bundle_installed_plugins', $results );
        update_option( 'wp_bundle_last_installed', time() );
        
        return $results;
    }
    
    /**
     * Verify status of bundled plugins
     * 
     * @return array
     */
    public function verify_plugins_status() {
        $bundled_plugins = $this->get_bundled_plugins();
        $status = array();
        
        foreach ( $bundled_plugins as $plugin ) {
            $plugin_data = $this->get_plugin_info( $plugin['path'] );
            
            if ( $plugin_data ) {
                $is_active = is_plugin_active( $plugin_data['plugin_file'] );
                
                $status[] = array(
                    'name' => $plugin_data['name'],
                    'slug' => $plugin_data['slug'],
                    'installed' => true,
                    'active' => $is_active,
                    'version' => $plugin_data['version']
                );
            }
        }
        
        return $status;
    }
    
    /**
     * Get plugin information
     * 
     * @param string $plugin_path
     * @return array|false
     */
    private function get_plugin_info( $plugin_path ) {
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugin_data = array();
        
        if ( is_dir( $plugin_path ) ) {
            // Find main plugin file in directory
            $files = glob( $plugin_path . '/*.php' );
            foreach ( $files as $file ) {
                $data = get_plugin_data( $file, false, false );
                if ( ! empty( $data['Name'] ) ) {
                    $plugin_data = $data;
                    $plugin_data['plugin_file'] = str_replace( WP_PLUGIN_DIR . '/', '', $file );
                    break;
                }
            }
        } elseif ( is_file( $plugin_path ) ) {
            $plugin_data = get_plugin_data( $plugin_path, false, false );
            $plugin_data['plugin_file'] = str_replace( WP_PLUGIN_DIR . '/', '', $plugin_path );
        }
        
        if ( ! empty( $plugin_data['Name'] ) ) {
            $plugin_data['slug'] = sanitize_title( $plugin_data['Name'] );
            return $plugin_data;
        }
        
        return false;
    }
    
    /**
     * Check if all bundled plugins are active
     * 
     * @return bool
     */
    public function all_plugins_active() {
        $status = $this->verify_plugins_status();
        
        foreach ( $status as $plugin ) {
            if ( ! $plugin['active'] ) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Resolve plugin path - handles nested directory structures
     * 
     * @param string $path Original path
     * @return string Resolved path to actual plugin
     */
    private function resolve_plugin_path( $path ) {
        // If it's a file, return as-is
        if ( is_file( $path ) ) {
            return $path;
        }
        
        // If it's a directory, check for nested structure
        if ( is_dir( $path ) ) {
            // Check if this directory contains only one subdirectory
            $contents = scandir( $path );
            $subdirs = array();
            
            foreach ( $contents as $item ) {
                if ( $item === '.' || $item === '..' ) {
                    continue;
                }
                
                $item_path = $path . '/' . $item;
                
                // If we find a PHP file directly, this is the plugin directory
                if ( is_file( $item_path ) && pathinfo( $item_path, PATHINFO_EXTENSION ) === 'php' ) {
                    // Check if it has a plugin header
                    $plugin_data = $this->quick_check_plugin_header( $item_path );
                    if ( $plugin_data ) {
                        return $path;
                    }
                }
                
                // If we find a subdirectory, collect it
                if ( is_dir( $item_path ) ) {
                    $subdirs[] = $item;
                }
            }
            
            // If there's exactly one subdirectory and no PHP files, it's likely nested
            if ( count( $subdirs ) === 1 && empty( $this->get_php_files( $path ) ) ) {
                $nested_path = $path . '/' . $subdirs[0];
                
                // Recursively resolve in case of multiple nesting levels
                return $this->resolve_plugin_path( $nested_path );
            }
        }
        
        return $path;
    }
    
    /**
     * Quick check if a file has a WordPress plugin header
     * 
     * @param string $file_path
     * @return bool
     */
    private function quick_check_plugin_header( $file_path ) {
        $contents = file_get_contents( $file_path, false, null, 0, 2048 );
        return ( stripos( $contents, 'Plugin Name:' ) !== false );
    }
    
    /**
     * Get PHP files in a directory
     * 
     * @param string $dir
     * @return array
     */
    private function get_php_files( $dir ) {
        $php_files = array();
        $files = scandir( $dir );
        
        foreach ( $files as $file ) {
            if ( $file === '.' || $file === '..' ) {
                continue;
            }
            
            $file_path = $dir . '/' . $file;
            if ( is_file( $file_path ) && pathinfo( $file_path, PATHINFO_EXTENSION ) === 'php' ) {
                $php_files[] = $file_path;
            }
        }
        
        return $php_files;
    }
    
    /**
     * Get installation statistics
     * 
     * @return array
     */
    public function get_stats() {
        $status = $this->verify_plugins_status();
        
        $total = count( $status );
        $active = 0;
        $inactive = 0;
        
        foreach ( $status as $plugin ) {
            if ( $plugin['active'] ) {
                $active++;
            } else {
                $inactive++;
            }
        }
        
        return array(
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        );
    }
}
