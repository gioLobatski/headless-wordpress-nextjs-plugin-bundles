<?php
/**
 * Installation Wizard Class
 * Handles the setup wizard that runs after plugin activation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Bundle_Installation_Wizard {
    
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
        add_action( 'admin_menu', array( $this, 'add_wizard_page' ) );
        add_action( 'admin_init', array( $this, 'maybe_redirect_to_wizard' ) );
        add_action( 'wp_ajax_wp_bundle_run_wizard_install', array( $this, 'ajax_wizard_install' ) );
    }
    
    /**
     * Add wizard page to admin menu
     */
    public function add_wizard_page() {
        add_submenu_page(
            'wp-plugin-bundle',
            __( 'Setup Wizard', 'wp-plugin-bundle' ),
            __( 'Setup Wizard', 'wp-plugin-bundle' ),
            'manage_options',
            'wp-bundle-wizard',
            array( $this, 'render_wizard_page' )
        );
    }
    
    /**
     * Redirect to wizard on first activation
     */
    public function maybe_redirect_to_wizard() {
        // Check if wizard needs to be shown
        $wizard_completed = get_option( 'wp_bundle_wizard_completed', false );
        $just_activated = get_option( 'wp_bundle_just_activated', false );
        
        if ( $just_activated && ! $wizard_completed ) {
            // Clear the flag
            delete_option( 'wp_bundle_just_activated' );
            
            // Redirect to wizard
            if ( isset( $_GET['page'] ) && $_GET['page'] !== 'wp-bundle-wizard' ) {
                wp_redirect( admin_url( 'admin.php?page=wp-bundle-wizard' ) );
                exit;
            }
        }
    }
    
    /**
     * Render wizard page
     */
    public function render_wizard_page() {
        $wizard_completed = get_option( 'wp_bundle_wizard_completed', false );
        ?>
        <div class="wrap wp-bundle-wizard">
            <?php if ( ! $wizard_completed ) : ?>
                <h1><?php echo esc_html__( 'Headless WordPress NEXT.js - Setup Wizard', 'wp-plugin-bundle' ); ?></h1>
                
                <div class="wp-bundle-wizard-container">
                    <div class="wp-bundle-wizard-intro">
                        <h2><?php echo esc_html__( 'Choose Your Site Type', 'wp-plugin-bundle' ); ?></h2>
                        <p><?php echo esc_html__( 'Select the type of website you\'re building. We\'ll install only the plugins you need.', 'wp-plugin-bundle' ); ?></p>
                    </div>
                    
                    <div class="wp-bundle-site-options">
                        <!-- Option 1: Basic/Portfolio Site -->
                        <div class="wp-bundle-site-option" data-type="basic">
                            <div class="option-icon">📄</div>
                            <h3><?php echo esc_html__( 'Basic or Portfolio Site', 'wp-plugin-bundle' ); ?></h3>
                            <p><?php echo esc_html__( 'Perfect for blogs, business sites, portfolios, and brochures', 'wp-plugin-bundle' ); ?></p>
                            
                            <div class="option-features">
                                <h4><?php echo esc_html__( 'Plugins that will be installed:', 'wp-plugin-bundle' ); ?></h4>
                                <ul>
                                    <li>✓ Advanced Custom Fields Pro</li>
                                    <li>✓ Classic Editor</li>
                                    <li>✓ Duplicate Page</li>
                                    <li>✓ iThemes Security Pro</li>
                                    <li>✓ Imagify (Image Optimization)</li>
                                    <li>✓ Rank Math SEO</li>
                                    <li>✓ SVG Support</li>
                                    <li>✓ InfiniteWP Client</li>
                                    <li>✓ WP Time Capsule (Backup)</li>
                                    <li>✓ WP GraphQL</li>
                                    <li>✓ WP GraphQL ACF</li>
                                    <li>✓ WP GraphQL IDE</li>
                                    <li>✓ WP GraphQL Rank Math</li>
                                    <li>✓ WP GraphQL Smart Cache</li>
                                    <li>✓ WP GraphQL Tax Query</li>
                                </ul>
                                <div class="excluded-note">
                                    <strong><?php echo esc_html__( 'Not included:', 'wp-plugin-bundle' ); ?></strong>
                                    <span><?php echo esc_html__( 'WooCommerce, WPGraphQL WooCommerce', 'wp-plugin-bundle' ); ?></span>
                                </div>
                            </div>
                            
                            <button type="button" class="button button-primary button-large wp-bundle-install-btn" data-site-type="basic">
                                <?php echo esc_html__( 'Install Basic Site Plugins', 'wp-plugin-bundle' ); ?>
                            </button>
                        </div>
                        
                        <!-- Option 2: Shop/Catalogue Site -->
                        <div class="wp-bundle-site-option" data-type="shop">
                            <div class="option-icon">🛒</div>
                            <h3><?php echo esc_html__( 'Shop or Catalogue Site', 'wp-plugin-bundle' ); ?></h3>
                            <p><?php echo esc_html__( 'Complete e-commerce solution with all features', 'wp-plugin-bundle' ); ?></p>
                            
                            <div class="option-features">
                                <h4><?php echo esc_html__( 'Plugins that will be installed:', 'wp-plugin-bundle' ); ?></h4>
                                <ul>
                                    <li>✓ All Basic Site plugins (15 plugins)</li>
                                    <li>✓ WooCommerce</li>
                                    <li>✓ WPGraphQL WooCommerce</li>
                                </ul>
                                <div class="total-count">
                                    <strong><?php echo esc_html__( 'Total:', 'wp-plugin-bundle' ); ?></strong>
                                    <span><?php echo esc_html__( '17 plugins for full e-commerce functionality', 'wp-plugin-bundle' ); ?></span>
                                </div>
                            </div>
                            
                            <button type="button" class="button button-primary button-large wp-bundle-install-btn" data-site-type="shop">
                                <?php echo esc_html__( 'Install All Plugins (Shop Mode)', 'wp-plugin-bundle' ); ?>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Progress Section -->
                    <div class="wp-bundle-install-progress" style="display: none;">
                        <div class="progress-header">
                            <h3><?php echo esc_html__( 'Installing Plugins...', 'wp-plugin-bundle' ); ?></h3>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar"></div>
                        </div>
                        <div class="progress-status">
                            <span class="spinner is-active"></span>
                            <span class="status-text"><?php echo esc_html__( 'Starting installation...', 'wp-plugin-bundle' ); ?></span>
                        </div>
                        <div class="progress-log"></div>
                    </div>
                </div>
                
            <?php else : ?>
                <!-- Wizard Completed -->
                <h1><?php echo esc_html__( 'Setup Complete!', 'wp-plugin-bundle' ); ?></h1>
                
                <div class="wp-bundle-wizard-complete">
                    <div class="complete-icon">✓</div>
                    <h2><?php echo esc_html__( 'Your plugins have been installed successfully!', 'wp-plugin-bundle' ); ?></h2>
                    <p><?php echo esc_html__( 'You can now activate and configure your plugins from the WordPress Plugins page.', 'wp-plugin-bundle' ); ?></p>
                    
                    <a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button button-primary button-large">
                        <?php echo esc_html__( 'Go to Plugins Page', 'wp-plugin-bundle' ); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for wizard installation
     */
    public function ajax_wizard_install() {
        check_ajax_referer( 'wp_bundle_wizard_nonce', 'nonce' );
        
        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'wp-plugin-bundle' ) ) );
        }
        
        $site_type = sanitize_text_field( $_POST['site_type'] );
        
        if ( ! in_array( $site_type, array( 'basic', 'shop' ), true ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid site type.', 'wp-plugin-bundle' ) ) );
        }
        
        // Get plugins to install based on site type
        $plugins_to_install = $this->get_plugins_for_site_type( $site_type );
        
        // Install plugins
        $results = $this->install_selected_plugins( $plugins_to_install );
        
        // Mark wizard as completed
        update_option( 'wp_bundle_wizard_completed', true );
        update_option( 'wp_bundle_site_type', $site_type );
        update_option( 'wp_bundle_installed_plugins_list', $plugins_to_install );
        
        if ( $results['success'] ) {
            wp_send_json_success( array(
                'message' => sprintf( __( '%d plugins installed successfully!', 'wp-plugin-bundle' ), $results['count'] ),
                'results' => $results['details']
            ) );
        } else {
            wp_send_json_error( array(
                'message' => __( 'Some plugins failed to install.', 'wp-plugin-bundle' ),
                'results' => $results['details']
            ) );
        }
    }
    
    /**
     * Get plugins to install based on site type
     * 
     * @param string $site_type
     * @return array
     */
    private function get_plugins_for_site_type( $site_type ) {
        // Plugins to exclude from basic sites
        $exclude_from_basic = array(
            'woocommerce',
            'wp-graphql-woocommerce'
        );
        
        $all_plugins = $this->plugin_manager->get_bundled_plugins();
        $plugins_to_install = array();
        
        foreach ( $all_plugins as $plugin ) {
            $plugin_name = strtolower( $plugin['name'] );
            
            // For basic sites, exclude WooCommerce-related plugins
            if ( $site_type === 'basic' ) {
                $should_exclude = false;
                foreach ( $exclude_from_basic as $exclude ) {
                    if ( strpos( $plugin_name, $exclude ) !== false ) {
                        $should_exclude = true;
                        break;
                    }
                }
                
                if ( ! $should_exclude ) {
                    $plugins_to_install[] = $plugin;
                }
            } else {
                // For shop sites, install everything
                $plugins_to_install[] = $plugin;
            }
        }
        
        return $plugins_to_install;
    }
    
    /**
     * Install selected plugins
     *
     * Downloads each plugin's ZIP from the configured GitHub Release
     * and installs it via WP_Bundle_Plugin_Installer::install_from_github().
     *
     * @param array $plugins Plugin descriptors from WP_Bundle_Plugin_Manager::get_bundled_plugins()
     * @return array
     */
    private function install_selected_plugins( $plugins ) {
        $installer = new WP_Bundle_Plugin_Installer();
        $results = array();
        $success_count = 0;
        $overall_success = true;

        foreach ( $plugins as $plugin ) {
            // Each $plugin entry comes from plugin-download-config.php and
            // contains 'name' (slug), 'zip_file' and 'version'.
            $slug      = isset( $plugin['name'] )     ? $plugin['name']     : '';
            $zip_file  = isset( $plugin['zip_file'] ) ? $plugin['zip_file'] : '';
            $version   = isset( $plugin['version'] )  ? $plugin['version']  : 'latest';

            if ( empty( $slug ) || empty( $zip_file ) ) {
                $overall_success = false;
                $results[] = array(
                    'name'    => $slug ?: __( '(unknown)', 'wp-plugin-bundle' ),
                    'success' => false,
                    'message' => __( 'Plugin configuration is missing slug or zip filename.', 'wp-plugin-bundle' ),
                );
                continue;
            }

            $result = $installer->install_from_github( $slug, $zip_file, $version );

            if ( $result['success'] ) {
                // Activate the plugin once installed.
                $activation_slug = ! empty( $result['plugin_slug'] ) ? $result['plugin_slug'] : $slug;
                $activation = $installer->activate_plugin( $activation_slug );
                $result['activation'] = $activation;

                if ( $activation['success'] ) {
                    $success_count++;
                } else {
                    $overall_success = false;
                }
            } else {
                $overall_success = false;
            }

            $results[] = array(
                'name'    => $slug,
                'success' => $result['success'],
                'message' => $result['message'],
            );
        }

        // Store installation results
        update_option( 'wp_bundle_installed_plugins', $results );
        update_option( 'wp_bundle_last_installed', time() );

        return array(
            'success' => $overall_success,
            'count'   => $success_count,
            'details' => $results
        );
    }
}
