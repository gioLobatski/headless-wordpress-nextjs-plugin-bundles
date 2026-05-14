<?php
$plugins_url = plugins_url();
?>
<link rel="stylesheet" type="text/css" href="<?php echo $plugins_url;?>/iwp-admin-panel-installer/css/style.css" />

<div class="wrap iwp-install">
    <h2 id="wfHeading">IWP Admin Panel Installer</h2>
    <div id="message" class="error"><p>This plugin is just an Installer. The InfiniteWP admin panel that you are installing will not be connected to this WordPress site in anyway (except using the same database).</p></div>
</div>
<?php
if (isset($_POST['iwp_conform'])) { ?>
    <div class="downloading_logs"> <?php
    $installer_folder_path = $_POST['iwp_install_folder'];
    $status = downloadPackage($installer_folder_path);
    $delete_plugin = 0;
    if (isset($_POST['delete_plugin'])) {
        $delete_plugin = 1;
    }
    if (!$status) {
        echo 'Error when reading file';
        exit;
    } else {
        $site_url = get_site_url()."/$installer_folder_path"."/install/index.php?pluginInstaller&ABSPATH=".ABSPATH."&folderPath=".$installer_folder_path."&deletePlugin=".$delete_plugin; ?>
        </div><?php
        ?>
        <script type="text/javascript">
        var info_text = document.getElementsByClassName('downloading_logs');
        info_text[0].remove();
        </script>
        <iframe src="<?php echo $site_url; ?>" style="width: 100%; height: 760px;"></iframe> <?php
    }
    exit;
}

$iwp_install_url = get_option( 'iwp_install_url' );
if ( $iwp_install_url != false ) {  exit; }  ?>
<div class="wrap iwp-install">
<h2 id="wfHeading">Folder to Install</h2>
    <div class="iwp-content">
        <div class="postbox-container">
            <form id="iwp_admin_login" action="<?php echo get_admin_url();?>admin.php?page=iwpinstall" method="post">
                <div class="postbox-container">
                    <div class="iwp_installtion_content postbox">
                        <h3 class="hndle" style="font-size: 14px;"><span>Folder to install</span></h3>
                        <table width="100%" border="0" class="is02">
                            <tr>
                                <td align="right" style="font-size: 14px;position: relative;top: -6px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis; width: 60%"><?php 
                                    echo get_site_url(); ?>/</td>
                                <td>
                                    <input id="iwp_install_folder" type="text" autocomplete="off" name="iwp_install_folder" value="iwp" style="width: 100%;">
                                    <div style=" font-size: 10px;position: relative;top: 5px;right: -1px;">If a folder already exists in that name, it will be overwritten.</div>
                                </td>
                            </tr>
                            <tr style="position: relative;">
                                <td style="font-size: 13px;position: relative;top: -4px;left: 6px; padding: 0px 0px 0px 5px;"><br><br><label>
                                    <input type="checkbox" name="delete_plugin" value="1"  style="width: 0px"><span style="width: 100%; position: absolute;">Delete this plugin after successful installation</span></label>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" class="is02">
                            <tr>
                                <div style="width: 100%; padding: 10px; box-sizing: border-box; -moz-box-sizing: border-box; border-top: 1px solid #EEE;">
                                    <input id="iwp_conform" class="button button-primary" type="submit" value="Download & Install" name="iwp_conform" style="float: right;"><div style="clear:both;"></div>
                                </div>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>