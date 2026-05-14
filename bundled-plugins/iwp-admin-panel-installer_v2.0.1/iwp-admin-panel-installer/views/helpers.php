<?php
function downloadPackage($path){
    try{
        global $wp_filesystem;
        global $wpdb;
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        $install_path = trailingslashit($GLOBALS['iwp_install_path']).$path;
        $upgrader = new Plugin_Upgrader();
        $upgrader->init();
        $upgrader->upgrade_strings();
        $upgrader->install_strings();
        @$upgrader->run(array(
            'package' => 'https://infinitewp.com/wp-content/plugins/download-monitor/download.php?id=11',
            'destination' =>$install_path,
            'clear_destination' => true, // Do overwrite files.
            'clear_working' => true,
            'hook_extra' => array()
        ));
        $target_dir = $wp_filesystem->find_folder($install_path);
        $target_file = trailingslashit($target_dir).'install/index.php';

        /* read the file */
        if($wp_filesystem->exists($target_file)){ //check for existence
            $installCode = $wp_filesystem->get_contents($target_file);
            if(!$installCode)
                return false;
        } else {
            return false;
        }
        return true;
    } catch (Exception $e) {
        echo $e;
    }
}