<?php
/*
Plugin Name: InfiniteWP Admin Panel Installer
Plugin URI: http://www.infinitewp.com
Description: InfiniteWP Admin Panel Installer
Version: 2.0.1
Author: Revmax Techonology Private Limitied
Author URI: http://www.revmakx.com/
License: GPL2
*/

$GLOBALS['iwp_install_root'] = realpath(dirname(__FILE__));
$GLOBALS['iwp_install_path'] = ABSPATH;
add_action("admin_menu", "iwp_install_addMenu");
add_action('admin_notices', 'iwp_install_admin_notice');
register_uninstall_hook('__FILE__','iwp_install_uninstall');

/*
 * Admin noties Hoock function
 */
function iwp_install_admin_notice() {
    $iwp_install_url = get_option( 'iwp_install_url' );
    if ( $iwp_install_url !== false ) {
        if(isset($_GET['page']) && $_GET['page']=='iwpinstall') {
            echo '<div class="updated">
                    <p>Your InfiniteWP admin panel is already installed here - <a href="'.$iwp_install_url.'" target="_blank">'.$iwp_install_url.'</a>  - You can delete this plugin now.</p>
                  </div>';
        }
    } else {
        echo '<div class="updated iwp_install_notice">
                <p>Welcome to InfiniteWP - You\'re almost ready to manage all your Wordpress sites Centrally :)</p>
                <p class="submit"><a class="button-primary" href="'.admin_url( 'admin.php?page=iwpinstall' ).'">Install InfiniteWP admin panel</a></p>
              </div>';
    }
}

/*
 * Admin Menu Hoock function
 */
function iwp_install_addMenu() {
    add_menu_page("InfiniteWP Admin Panel Installer", "IWP Installer", "activate_plugins", "iwpinstall", "iwp_install", plugins_url( 'iwp-admin-panel-installer/images/iwp_white_16.png',dirname(__FILE__)), 7575);
}

/*
 * IWP Installer Page code start from here
 */
function iwp_install() {
        $GLOBALS['maximumExecutionTime'] = 300 + ini_get('max_execution_time');
        //$maximumExecutionTime = 300 + ini_get('max_execution_time');
        @set_time_limit($GLOBALS['maximumExecutionTime']);//300 => 5 mins
        require_once($GLOBALS['iwp_install_root'] . "/views/helpers.php");
        require($GLOBALS['iwp_install_root'] . "/views/install.php");
}

/**
 * Initializes the file system
 * 
 */
function iwp_install_init_filesystem($form_url,$pathToCheck, $extra_fields=null) {
   global $wp_filesystem;
   $method = get_filesystem_method(array(),$pathToCheck);
   /* first attempt to get credentials */
    if (false === ($creds = request_filesystem_credentials($form_url, $method, false, $pathToCheck, $extra_fields))) {
        
        /**
         * if we comes here - we don't have credentials
         * so the request for them is displaying
         * no need for further processing
         **/
        return false;
    }

    /* now we got some credentials - try to use them*/        
    if (!WP_Filesystem($creds)) {
        
        /* incorrect connection data - ask for credentials again, now with error message */
        request_filesystem_credentials($form_url, $method, true, $pathToCheck, $extra_fields);
        return false;
    }
    
    return true; //filesystem object successfully initiated
}

function iwp_install_config() {
    $GLOBALS['']='';
}

/*
 * Admin UnInstall Hook
 */
function iwp_install_uninstall() {
    
}