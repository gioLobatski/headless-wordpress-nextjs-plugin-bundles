<?php
/**
 * Plugin Name: Headless WordPress NEXT.js Plugin Bundles
 * Plugin URI: https://high6.com/
 * Description: A bundled collection of essential WordPress plugins for headless WordPress with NEXT.js, with automatic installation and activation.
 * Version: 1.3.0
 * Author: High6-Gio
 * Author URI: https://high6.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-plugin-bundle
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'WP_BUNDLE_VERSION', '1.3.0' );
define( 'WP_BUNDLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_BUNDLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_BUNDLE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WP_BUNDLE_BUNDLED_PLUGINS_DIR', WP_BUNDLE_PLUGIN_DIR . 'bundled-plugins/' );
define( 'WP_BUNDLE_NAME', 'Headless WordPress NEXT.js Plugin Bundles' );

/**
 * Main Plugin Bundle Class
 */
class WP_Plugin_Bundle {
    
    /**
     * Singleton instance
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Load required files
     */
    private function load_dependencies() {
        require_once WP_BUNDLE_PLUGIN_DIR . 'includes/class-plugin-installer.php';
        require_once WP_BUNDLE_PLUGIN_DIR . 'includes/class-plugin-manager.php';
        require_once WP_BUNDLE_PLUGIN_DIR . 'admin/class-admin.php';
        require_once WP_BUNDLE_PLUGIN_DIR . 'admin/class-installation-wizard.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set flag to show wizard (don't auto-install anymore)
        add_option( 'wp_bundle_just_activated', true );
        add_option( 'wp_bundle_version', WP_BUNDLE_VERSION );
        add_option( 'wp_bundle_activated', time() );
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Optionally deactivate bundled plugins
        // $this->deactivate_bundled_plugins();
        
        flush_rewrite_rules();
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain( 'wp-plugin-bundle', false, dirname( WP_BUNDLE_PLUGIN_BASENAME ) . '/languages' );
        
        // Initialize admin and wizard
        new WP_Bundle_Admin();
        new WP_Bundle_Installation_Wizard();
    }
    
    /**
     * Install bundled plugins
     */
    private function install_bundled_plugins() {
        $plugin_manager = new WP_Bundle_Plugin_Manager();
        $plugin_manager->install_bundled_plugins();
    }
    
    /**
     * Check if bundled plugins are active
     */
    public function check_bundled_plugins() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        
        $plugin_manager = new WP_Bundle_Plugin_Manager();
        $plugin_manager->verify_plugins_status();
    }
    
    /**
     * Get bundled plugins list
     */
    public function get_bundled_plugins() {
        $plugin_manager = new WP_Bundle_Plugin_Manager();
        return $plugin_manager->get_bundled_plugins();
    }
}

/**
 * Initialize the plugin
 */
function wp_plugin_bundle_init() {
    return WP_Plugin_Bundle::get_instance();
}

// Start the plugin
wp_plugin_bundle_init();
