<?php
/**
 * Plugin Installer Class
 * Handles installation and activation of bundled plugins
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Bundle_Plugin_Installer {
    
    /**
     * Install a plugin from a local file
     * 
     * @param string $plugin_file Path to the plugin file or directory
     * @return array Installation result
     */
    public function install_from_local( $plugin_file ) {
        if ( ! file_exists( $plugin_file ) ) {
            return array(
                'success' => false,
                'message' => __( 'Plugin file not found.', 'wp-plugin-bundle' )
            );
        }
        
        // Include WordPress plugin installation functions
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        if ( ! function_exists( 'WP_PLUGIN_DIR' ) ) {
            require_once ABSPATH . 'wp-admin/includes/admin.php';
        }
        
        try {
            // Get plugin data
            $plugin_data = $this->get_plugin_data( $plugin_file );
            
            if ( empty( $plugin_data['Name'] ) ) {
                return array(
                    'success' => false,
                    'message' => __( 'Invalid plugin file.', 'wp-plugin-bundle' )
                );
            }
            
            // Check if plugin is already installed
            $plugin_slug = $plugin_data['slug'];
            $destination = WP_PLUGIN_DIR . '/' . $plugin_slug;
            
            if ( is_dir( $destination ) ) {
                return array(
                    'success' => true,
                    'message' => sprintf( __( 'Plugin "%s" is already installed.', 'wp-plugin-bundle' ), $plugin_data['Name'] ),
                    'plugin_slug' => $plugin_slug,
                    'already_installed' => true
                );
            }
            
            // Copy plugin to WordPress plugins directory
            $result = $this->copy_plugin( $plugin_file, $destination );
            
            if ( $result ) {
                return array(
                    'success' => true,
                    'message' => sprintf( __( 'Plugin "%s" installed successfully.', 'wp-plugin-bundle' ), $plugin_data['Name'] ),
                    'plugin_slug' => $plugin_slug,
                    'plugin_data' => $plugin_data
                );
            } else {
                return array(
                    'success' => false,
                    'message' => sprintf( __( 'Failed to install plugin "%s".', 'wp-plugin-bundle' ), $plugin_data['Name'] )
                );
            }
            
        } catch ( Exception $e ) {
            return array(
                'success' => false,
                'message' => $e->getMessage()
            );
        }
    }
    
    /**
     * Get plugin data from file
     * 
     * @param string $plugin_file
     * @return array
     */
    private function get_plugin_data( $plugin_file ) {
        $plugin_data = array();
        
        // If it's a directory, look for the main plugin file
        if ( is_dir( $plugin_file ) ) {
            $files = glob( $plugin_file . '/*.php' );
            foreach ( $files as $file ) {
                $data = get_plugin_data( $file, false, false );
                if ( ! empty( $data['Name'] ) ) {
                    $plugin_data = $data;
                    $plugin_data['main_file'] = $file;
                    break;
                }
            }
        } else {
            $plugin_data = get_plugin_data( $plugin_file, false, false );
            $plugin_data['main_file'] = $plugin_file;
        }
        
        // Generate slug from plugin name
        if ( ! empty( $plugin_data['Name'] ) ) {
            $plugin_data['slug'] = sanitize_title( $plugin_data['Name'] );
        }
        
        return $plugin_data;
    }
    
    /**
     * Copy plugin to WordPress plugins directory
     * 
     * @param string $source Source path
     * @param string $destination Destination path
     * @return bool
     */
    private function copy_plugin( $source, $destination ) {
        // Include filesystem functions
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        WP_Filesystem();
        global $wp_filesystem;
        
        try {
            if ( is_dir( $source ) ) {
                // Copy directory
                return $this->copy_directory( $source, $destination );
            } else {
                // Create directory for single file plugin
                $plugin_dir = dirname( $destination );
                if ( ! wp_mkdir_p( $plugin_dir ) ) {
                    return false;
                }
                
                // Copy single file
                return $wp_filesystem->copy( $source, $destination, true );
            }
        } catch ( Exception $e ) {
            return false;
        }
    }
    
    /**
     * Copy directory recursively
     * 
     * @param string $source
     * @param string $destination
     * @return bool
     */
    private function copy_directory( $source, $destination ) {
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        WP_Filesystem();
        global $wp_filesystem;
        
        // Create destination directory
        if ( ! wp_mkdir_p( $destination ) ) {
            return false;
        }
        
        // Copy all files and directories
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $source, RecursiveDirectoryIterator::SKIP_DOTS ),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ( $iterator as $item ) {
            $dest_path = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            if ( $item->isDir() ) {
                if ( ! wp_mkdir_p( $dest_path ) ) {
                    return false;
                }
            } else {
                if ( ! $wp_filesystem->copy( $item->getPathname(), $dest_path, true ) ) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Activate a plugin
     * 
     * @param string $plugin_slug Plugin slug
     * @return array Activation result
     */
    public function activate_plugin( $plugin_slug ) {
        // Find the plugin file
        $plugin_file = $this->find_plugin_file( $plugin_slug );
        
        if ( empty( $plugin_file ) ) {
            return array(
                'success' => false,
                'message' => __( 'Plugin file not found for activation.', 'wp-plugin-bundle' )
            );
        }
        
        // Check if already active
        if ( is_plugin_active( $plugin_file ) ) {
            return array(
                'success' => true,
                'message' => sprintf( __( 'Plugin "%s" is already active.', 'wp-plugin-bundle' ), $plugin_slug ),
                'already_active' => true
            );
        }
        
        // Activate the plugin
        $result = activate_plugin( $plugin_file, '', false, true );
        
        if ( is_wp_error( $result ) ) {
            return array(
                'success' => false,
                'message' => $result->get_error_message()
            );
        }
        
        return array(
            'success' => true,
            'message' => sprintf( __( 'Plugin "%s" activated successfully.', 'wp-plugin-bundle' ), $plugin_slug )
        );
    }
    
    /**
     * Find plugin main file
     * 
     * @param string $plugin_slug
     * @return string
     */
    private function find_plugin_file( $plugin_slug ) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugins = get_plugins();
        
        foreach ( $plugins as $plugin_file => $plugin_data ) {
            // Check if plugin directory matches slug
            $plugin_dir = dirname( $plugin_file );
            if ( sanitize_title( $plugin_data['Name'] ) === $plugin_slug || $plugin_dir === $plugin_slug ) {
                return $plugin_file;
            }
        }
        
        return '';
    }
}
