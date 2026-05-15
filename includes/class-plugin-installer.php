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
     * GitHub repository URL for plugin downloads
     * 
     * @var string
     */
    private $download_url;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Default: Use GitHub releases from the repository
        $this->download_url = get_option( 'wp_bundle_download_url', 'https://github.com/gioLobatski/headless-wordpress-plugin-downloads/releases/download' );
    }
    
    /**
     * Install a plugin from GitHub Releases
     * 
     * @param string $plugin_slug Plugin slug/directory name
     * @param string $zip_filename ZIP file name in the release
     * @param string $version Release version (default: latest)
     * @return array Installation result
     */
    public function install_from_github( $plugin_slug, $zip_filename, $version = 'latest' ) {
        // Build download URL
        $download_url = rtrim( $this->download_url, '/' ) . '/' . $version . '/' . $zip_filename;
        
        // Download the ZIP file
        $temp_file = $this->download_plugin_zip( $download_url );
        
        if ( is_wp_error( $temp_file ) ) {
            return array(
                'success' => false,
                'message' => $temp_file->get_error_message()
            );
        }
        
        // Extract and install
        return $this->install_from_zip( $temp_file, $plugin_slug );
    }
    
    /**
     * Download plugin ZIP file from URL
     * 
     * @param string $url Download URL
     * @return string|WP_Error Path to temp file or error
     */
    private function download_plugin_zip( $url ) {
        // Include required WordPress functions
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        // Create temporary file
        $temp_file = wp_tempnam( $url );
        
        if ( ! $temp_file ) {
            return new WP_Error(
                'temp_file_failed',
                __( 'Failed to create temporary file.', 'wp-plugin-bundle' )
            );
        }
        
        // Download the file
        $response = wp_safe_remote_get( $url, array(
            'timeout' => 300,
            'stream' => true,
            'filename' => $temp_file
        ) );
        
        if ( is_wp_error( $response ) ) {
            unlink( $temp_file );
            return $response;
        }
        
        // Check response code
        $response_code = wp_remote_retrieve_response_code( $response );
        if ( $response_code !== 200 ) {
            unlink( $temp_file );
            return new WP_Error(
                'download_failed',
                sprintf( __( 'Failed to download plugin. HTTP status: %d', 'wp-plugin-bundle' ), $response_code )
            );
        }
        
        // Verify file size
        $file_size = filesize( $temp_file );
        if ( $file_size < 100 ) {
            unlink( $temp_file );
            return new WP_Error(
                'invalid_file',
                __( 'Downloaded file is invalid or corrupted.', 'wp-plugin-bundle' )
            );
        }
        
        return $temp_file;
    }
    
    /**
     * Install plugin from ZIP file
     * 
     * @param string $zip_file Path to ZIP file
     * @param string $plugin_slug Expected plugin slug
     * @return array Installation result
     */
    private function install_from_zip( $zip_file, $plugin_slug ) {
        // Include WordPress filesystem functions
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        WP_Filesystem();
        global $wp_filesystem;
        
        $destination = WP_PLUGIN_DIR . '/' . $plugin_slug;
        
        // Check if already installed
        if ( is_dir( $destination ) ) {
            // Clean up temp file
            unlink( $zip_file );
            
            return array(
                'success' => true,
                'message' => sprintf( __( 'Plugin "%s" is already installed.', 'wp-plugin-bundle' ), $plugin_slug ),
                'plugin_slug' => $plugin_slug,
                'already_installed' => true
            );
        }
        
        // Extract ZIP
        $temp_dir = WP_PLUGIN_DIR . '/temp-' . $plugin_slug . '-' . time();
        
        if ( ! wp_mkdir_p( $temp_dir ) ) {
            unlink( $zip_file );
            return array(
                'success' => false,
                'message' => __( 'Failed to create temporary directory.', 'wp-plugin-bundle' )
            );
        }
        
        // Use WordPress unzip function
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
        
        $unzip_result = unzip_file( $zip_file, $temp_dir );
        
        // Clean up ZIP file
        unlink( $zip_file );
        
        if ( is_wp_error( $unzip_result ) ) {
            // Clean up temp directory
            $this->delete_directory( $temp_dir );
            
            return array(
                'success' => false,
                'message' => $unzip_result->get_error_message()
            );
        }
        
        // Find the actual plugin directory (handle nested structure)
        $plugin_dir = $this->find_plugin_directory( $temp_dir, $plugin_slug );
        
        if ( ! $plugin_dir ) {
            $this->delete_directory( $temp_dir );
            return array(
                'success' => false,
                'message' => __( 'Could not find valid plugin structure in ZIP file.', 'wp-plugin-bundle' )
            );
        }
        
        // Move to final destination
        $move_result = $this->move_directory( $plugin_dir, $destination );
        
        // Clean up temp directory
        if ( $temp_dir !== $plugin_dir ) {
            $this->delete_directory( $temp_dir );
        }
        
        if ( ! $move_result ) {
            return array(
                'success' => false,
                'message' => sprintf( __( 'Failed to install plugin "%s".', 'wp-plugin-bundle' ), $plugin_slug )
            );
        }
        
        // Get plugin data
        $plugin_data = $this->get_plugin_data_from_dir( $destination );
        
        return array(
            'success' => true,
            'message' => sprintf( __( 'Plugin "%s" installed successfully.', 'wp-plugin-bundle' ), $plugin_data['Name'] ?: $plugin_slug ),
            'plugin_slug' => $plugin_slug,
            'plugin_data' => $plugin_data
        );
    }
    
    /**
     * Find plugin directory in extracted files
     * 
     * @param string $base_dir Base extraction directory
     * @param string $expected_slug Expected plugin slug
     * @return string|false Plugin directory path or false
     */
    private function find_plugin_directory( $base_dir, $expected_slug ) {
        // Check if base directory is the plugin itself
        if ( $this->is_valid_plugin_dir( $base_dir ) ) {
            return $base_dir;
        }
        
        // Look for subdirectories
        $items = scandir( $base_dir );
        
        foreach ( $items as $item ) {
            if ( $item === '.' || $item === '..' ) {
                continue;
            }
            
            $path = $base_dir . '/' . $item;
            
            if ( is_dir( $path ) ) {
                // Check if this directory matches the expected slug
                if ( sanitize_title( $item ) === $expected_slug || strpos( strtolower( $item ), strtolower( $expected_slug ) ) !== false ) {
                    if ( $this->is_valid_plugin_dir( $path ) ) {
                        return $path;
                    }
                }
                
                // Recursively check subdirectories
                $found = $this->find_plugin_directory( $path, $expected_slug );
                if ( $found ) {
                    return $found;
                }
            }
        }
        
        // Return first valid plugin directory found
        foreach ( $items as $item ) {
            if ( $item === '.' || $item === '..' ) {
                continue;
            }
            
            $path = $base_dir . '/' . $item;
            if ( is_dir( $path ) && $this->is_valid_plugin_dir( $path ) ) {
                return $path;
            }
        }
        
        return false;
    }
    
    /**
     * Check if directory contains a valid WordPress plugin
     * 
     * @param string $dir Directory path
     * @return bool
     */
    private function is_valid_plugin_dir( $dir ) {
        if ( ! is_dir( $dir ) ) {
            return false;
        }
        
        // Look for PHP files with plugin header
        $php_files = glob( $dir . '/*.php' );
        
        foreach ( $php_files as $file ) {
            $content = file_get_contents( $file, false, null, 0, 2048 );
            if ( stripos( $content, 'Plugin Name:' ) !== false ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Move directory from source to destination
     * 
     * @param string $source
     * @param string $destination
     * @return bool
     */
    private function move_directory( $source, $destination ) {
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        WP_Filesystem();
        global $wp_filesystem;
        
        // Use WordPress copy and delete
        $result = $this->copy_directory( $source, $destination );
        
        if ( $result ) {
            $this->delete_directory( $source );
        }
        
        return $result;
    }
    
    /**
     * Delete directory recursively
     * 
     * @param string $dir
     * @return bool
     */
    private function delete_directory( $dir ) {
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        
        WP_Filesystem();
        global $wp_filesystem;
        
        return $wp_filesystem->rmdir( $dir, true );
    }
    
    /**
     * Get plugin data from directory
     * 
     * @param string $dir
     * @return array
     */
    private function get_plugin_data_from_dir( $dir ) {
        if ( ! function_exists( 'get_plugin_data' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $php_files = glob( $dir . '/*.php' );
        
        foreach ( $php_files as $file ) {
            $data = get_plugin_data( $file, false, false );
            if ( ! empty( $data['Name'] ) ) {
                $data['slug'] = sanitize_title( $data['Name'] );
                $data['main_file'] = $file;
                return $data;
            }
        }
        
        return array( 'Name' => '', 'slug' => basename( $dir ) );
    }
    
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
