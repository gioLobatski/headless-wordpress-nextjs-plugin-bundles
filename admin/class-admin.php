<?php
/**
 * Admin Class
 * Handles the admin interface for the plugin bundle
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Bundle_Admin {
    
    /**
     * Plugin manager instance
     */
    private $plugin_manager;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->plugin_manager = new WP_Bundle_Plugin_Manager();
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'wp_ajax_wp_bundle_reinstall_plugins', array( $this, 'ajax_reinstall_plugins' ) );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Plugin Bundle', 'wp-plugin-bundle' ),
            __( 'NEXT.js Bundle', 'wp-plugin-bundle' ),
            'manage_options',
            'wp-plugin-bundle',
            array( $this, 'render_admin_page' ),
            'dashicons-admin-plugins',
            100
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets( $hook ) {
        // Get current page from URL
        $current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';
        
        // Load wizard assets on wizard page
        if ( 'wp-bundle-wizard' === $current_page ) {
            wp_enqueue_style(
                'wp-bundle-wizard',
                WP_BUNDLE_PLUGIN_URL . 'assets/css/wizard.css',
                array(),
                WP_BUNDLE_VERSION
            );
            
            wp_enqueue_script(
                'wp-bundle-wizard',
                WP_BUNDLE_PLUGIN_URL . 'assets/js/wizard.js',
                array( 'jquery' ),
                WP_BUNDLE_VERSION,
                true
            );
            
            wp_localize_script( 'wp-bundle-wizard', 'wpBundleWizardAjax', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'wp_bundle_wizard_nonce' ),
                'pluginsUrl' => admin_url( 'plugins.php' )
            ) );
            return;
        }
        
        // Load admin dashboard assets on main page
        if ( 'wp-plugin-bundle' === $current_page ) {
            wp_enqueue_style(
                'wp-bundle-admin',
                WP_BUNDLE_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                WP_BUNDLE_VERSION
            );
            
            wp_enqueue_script(
                'wp-bundle-admin',
                WP_BUNDLE_PLUGIN_URL . 'assets/js/admin.js',
                array( 'jquery' ),
                WP_BUNDLE_VERSION,
                true
            );
            
            wp_localize_script( 'wp-bundle-admin', 'wpBundleAjax', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'wp_bundle_nonce' ),
                'reinstallText' => __( 'Reinstalling plugins...', 'wp-plugin-bundle' ),
                'successText' => __( 'Plugins reinstalled successfully!', 'wp-plugin-bundle' ),
                'errorText' => __( 'Error reinstalling plugins.', 'wp-plugin-bundle' )
            ) );
        }
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $stats = $this->plugin_manager->get_stats();
        $plugins = $this->plugin_manager->verify_plugins_status();
        ?>
        <div class="wrap wp-bundle-admin">
            <h1><?php echo esc_html__( 'Headless WordPress NEXT.js Plugin Bundle', 'wp-plugin-bundle' ); ?></h1>
            
            <div class="wp-bundle-dashboard">
                <!-- Stats Cards -->
                <div class="wp-bundle-stats">
                    <div class="wp-bundle-stat-card">
                        <div class="stat-number"><?php echo esc_html( $stats['total'] ); ?></div>
                        <div class="stat-label"><?php echo esc_html__( 'Total Plugins', 'wp-plugin-bundle' ); ?></div>
                    </div>
                    <div class="wp-bundle-stat-card success">
                        <div class="stat-number"><?php echo esc_html( $stats['active'] ); ?></div>
                        <div class="stat-label"><?php echo esc_html__( 'Active', 'wp-plugin-bundle' ); ?></div>
                    </div>
                    <div class="wp-bundle-stat-card warning">
                        <div class="stat-number"><?php echo esc_html( $stats['inactive'] ); ?></div>
                        <div class="stat-label"><?php echo esc_html__( 'Inactive', 'wp-plugin-bundle' ); ?></div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="wp-bundle-actions">
                    <button type="button" id="wp-bundle-reinstall" class="button button-primary">
                        <?php echo esc_html__( 'Reinstall All Plugins', 'wp-plugin-bundle' ); ?>
                    </button>
                    <button type="button" id="wp-bundle-refresh-status" class="button">
                        <?php echo esc_html__( 'Refresh Status', 'wp-plugin-bundle' ); ?>
                    </button>
                </div>
                
                <!-- Plugins List -->
                <div class="wp-bundle-plugins-list">
                    <h2><?php echo esc_html__( 'Bundled Plugins', 'wp-plugin-bundle' ); ?></h2>
                    
                    <?php if ( empty( $plugins ) ) : ?>
                        <div class="wp-bundle-notice notice notice-info">
                            <p><?php echo esc_html__( 'No plugins are currently bundled. Add plugins to the bundled-plugins/ directory.', 'wp-plugin-bundle' ); ?></p>
                        </div>
                    <?php else : ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php echo esc_html__( 'Plugin Name', 'wp-plugin-bundle' ); ?></th>
                                    <th><?php echo esc_html__( 'Version', 'wp-plugin-bundle' ); ?></th>
                                    <th><?php echo esc_html__( 'Status', 'wp-plugin-bundle' ); ?></th>
                                    <th><?php echo esc_html__( 'Actions', 'wp-plugin-bundle' ); ?></th>
                                </tr>
                            </thead>
                            <tbody id="wp-bundle-plugins-table">
                                <?php foreach ( $plugins as $plugin ) : ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo esc_html( $plugin['name'] ); ?></strong>
                                            <br><small><?php echo esc_html( $plugin['slug'] ); ?></small>
                                        </td>
                                        <td><?php echo esc_html( $plugin['version'] ); ?></td>
                                        <td>
                                            <?php if ( $plugin['active'] ) : ?>
                                                <span class="wp-bundle-status active"><?php echo esc_html__( 'Active', 'wp-plugin-bundle' ); ?></span>
                                            <?php else : ?>
                                                <span class="wp-bundle-status inactive"><?php echo esc_html__( 'Inactive', 'wp-plugin-bundle' ); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo esc_url( admin_url( 'plugins.php?s=' . urlencode( $plugin['name'] ) ) ); ?>" class="button button-small">
                                                <?php echo esc_html__( 'View in Plugins', 'wp-plugin-bundle' ); ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <!-- Instructions -->
                <div class="wp-bundle-instructions">
                    <h2><?php echo esc_html__( 'How to Add Plugins', 'wp-plugin-bundle' ); ?></h2>
                    <ol>
                        <li><?php echo esc_html__( 'Download or copy your plugin files', 'wp-plugin-bundle' ); ?></li>
                        <li><?php echo esc_html__( 'Place them in the bundled-plugins/ directory', 'wp-plugin-bundle' ); ?></li>
                        <li><?php echo esc_html__( 'Plugins can be single .php files or plugin directories', 'wp-plugin-bundle' ); ?></li>
                        <li><?php echo esc_html__( 'Activate and manage plugins from the WordPress Plugins page', 'wp-plugin-bundle' ); ?></li>
                    </ol>
                    
                    <div class="wp-bundle-path">
                        <strong><?php echo esc_html__( 'Bundled Plugins Directory:', 'wp-plugin-bundle' ); ?></strong>
                        <code><?php echo esc_html( WP_BUNDLE_BUNDLED_PLUGINS_DIR ); ?></code>
                    </div>
                </div>
            </div>
            
            <div id="wp-bundle-ajax-message" class="notice notice-success is-hidden" style="margin-top: 20px;">
                <p></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for reinstalling plugins
     */
    public function ajax_reinstall_plugins() {
        check_ajax_referer( 'wp_bundle_nonce', 'nonce' );
        
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-plugin-bundle' ) ) );
        }
        
        $result = $this->plugin_manager->install_bundled_plugins();
        
        if ( $result ) {
            wp_send_json_success( array(
                'message' => sprintf( __( '%d plugins reinstalled successfully.', 'wp-plugin-bundle' ), count( $result ) ),
                'results' => $result
            ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Failed to reinstall plugins.', 'wp-plugin-bundle' ) ) );
        }
    }
}
