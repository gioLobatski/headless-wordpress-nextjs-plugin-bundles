=== Backup and Staging by WP Time Capsule ===

Contributors: dark-prince, amritanandh, WPTimeCapsule
Tags: backup, staging, migration, backup before update, auto updates.
Requires at least: 3.9.14
Tested up to: 6.9.4
Stable tag: 1.22.26
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Backup and Staging by WP Time Capsule is an automated incremental backup plugin that backs up your website changes as per your schedule to Dropbox, Google Drive, Amazon S3, Wasabi and Backblaze B2 Cloud.

== Description ==

[WP Time Capsule](https://wptimecapsule.com/ "Incremental Backup for WordPress") was created to ensure peace of mind with WP updates and put the fun back into WordPress. It uses the cloud apps' native file versioning system to detect  changes and backs up just the changed files and db entries to your account.

You need to sign up for an account on our website https://service.wptimecapsule.com to get a 30 days full featured trial.

<br> 
**With WP Time Capsule you can**<br>
<br>
**Backup in real-time**
You can backup your site in real-time which means you will now be able to revert your site to how it was just an hour ago. Also, you can change the backup interval to every 6 hours or 12 hours or daily. <br>
<br>
**Staging**
With a single click staging it's a breeze to test any change you are planning to do on your site. Test your updates on staging with a single click<br>
<br>
**Auto backup and update**
We automatically backup before each update, if an update causes any undesired change. You are a click away from restoring.<br>
<br>
**Encrypted DB backups & GDPR Compatible**
You can encrypt your DB backups to add an extra layer of security and makes your backup GDPR compatible. <br>
<br>
**Clone/Migrate**
You can now use the WPTC backup to clone or migrate your site to a new location at ease.<br>
<br>

**How is WP Time Capsule different than other backup plugins?**
<br>
WPTC is unique in 4 ways -<br>
1. It backs up and restores only the changed files & DB and not the entire site every time.<br>
2. The files & DB are stored in your cloud storage app - Amazon S3, Wasabi, Backblaze, Dropbox or Google Drive.
3. We have used the cloud apps' native file versioning system to detect changes and maintain file versions. So backups and restores are as reliable as they get.
4. Most importantly, you can backup your site in real-time which means you will now be able to revert your site to how it was just an hour ago. Also, you can change the backup interval to every 6 hours or 12 hours or daily. 
<br><br>
**How does it work?**
<br>
1. Sign up an account on our website https://wptimecapsule.com and you will get a 30 days full featured trial.<br>
2. Install the plugin and login with your wptimecapsule.com account.<br>
3. Next, connect the cloud app where you want to store the backup files. You can use Amazon S3, Wasabi, Dropbox or Google Drive.<br>
4. Once you connect the cloud app, we will automatically begin backing up your complete website to your cloud app account.<br><br>
After the first full backup is taken, you can schedule a time for WPTC to backup your websites. We will take care of your backups from here on.
This being done you will officially be *disaster-ready*. :)
<br><br>
**Backup**: Looks for files & DB changes since the last backup and uploads only the changes. The data is then stored securely in your cloud app account.
<br>
**Restore**: Checks revision history and displays the same. You can restore the site to any point in time or restore specific files & DB.
<br><br>
**How is it better?**<br>
BACKUP METHOD<br>
Traditionally - Backups are compressed and zipped. The Bad: Heavy server resource consumption.<br>
WPTC - No zipping. Changed files are directly dropped into your cloud account. The Good: ***Uses considerably less server resources***
<br><br>
BACKUP FILE<br>
Traditionally - Multiple zip files are created every time you backup. The Bad: Precious storage space is wasted.<br>
WPTC - Backs up incrementally. No multiple copies of files. The Good: ***Uses far less disk space***
<br><br>
RESTORE<br>
Traditionally - Unzip backup and restore the whole site. The Bad: Consumes time and server resource.<br>
WPTC - Restores only selected files. The Good: ***Faster restore and you can restore even if you don't have access to your site or plugin.***
<br><br>
Check out our [Knowledge Base](https://docs.wptimecapsule.com/)
Visit us at [wptimecapsule.com](https://wptimecapsule.com/ "Incremental Backup for WordPress")

Credits: Michael De Wildt for his WordPress Backup to Dropbox plugin based on which this plugin is being developed.

== Installation ==
= Minimum Requirements =
 * PHP version 5.3.1 or greater (recommended: PHP 7.2.5 or greater)
 * MySQL version 5.0.15 or greater (recommended: MySQL 5.5 or greater)

= Installation =
Installing WP Time Capsule is simple and easy. Install it like any other WordPress plugin.
<ol>
  <li>Sign up an account on our website https://wptimecapsule.com and you will get a 30 days full featured trial.</li>
  <li>Login to your WordPress dashboard, under Plugins click Add New</li>
  <li>In the plugin repository search for 'WP Time Capsule' or upload the plugin zip file and install it</li>
  <li>After installation, login with your wptimecapsule.com account</li>
  <li>Then, connect with the cloud app that you want to use to backup your site</li>
  <li>Once the cloud app is connected, you can schedule your backup time and we will begin backing up the website to your cloud app according to schedule.</li>
</ol>

== Screenshots ==

1. **Backup calendar view** - You can view and restore files + database from a calendar view.
2. **Restore specific files** - View a list of files that have changed and been backed up and selectively restore them.
3. **Warp back your site in time** - You can restore the complete site back to a specific point in time.
4. **One click staging** - Set up your staging site in a single click.
5. **Backup Before Update** - Configure Auto and Backup Before Updates for your plugins, themes, and core to update automatically on the scheduled time.
6. **Database Encyption** - Encrypt your Database backup to add an extra security layer.
7. **Cloning** - Use the meta SQL file from your cloud storage to clone/migrate your site to a new location.

== Changelog ==

= 1.22.26 =
*Release Date - 07 May 2026*

* Fix: Prevent bulk adding sites.

* Improvement: Tested upto WP 6.9.4.

= 1.22.25 =
*Release Date - 22 Sep 2025*

* Fix: DB backup failed in a few cases when the Hummingbird plugin is active.

* Improvement: Added new bucket regions in Wasabi and Backblaze.
* Improvement: Tested upto WP 6.8.2.

= 1.22.24 =
*Release Date - 1 May 2025*

* Fix : Improved bridge files.

* Improvement : Tested upto WP 6.8.1.

= 1.22.23 =
*Release Date - 13 Nov 2024*

* Improvement : Manual decrypting of the encrypted DB file feature is improved. Now file extensions are properly checked.
* Improvement : Tested upto WP 6.7.

= 1.22.22 =
*Release Date - 17 Sep 2024*

* Improvement : Manual decrypting of the encrypted DB file feature is improved. Now file extensions are properly checked.
* Improvement : Improved decoding mechanism.
* Improvement : Improved activity log for queries.

* Fix : Update in staging did not work in a few cases.
* Fix : Staging to live failed in a few cases.

= 1.22.21 =
*Release Date - 11 Jul 2024*

* Improvement : Improved authentication mechanism.

= 1.22.20 =
*Release Date - 03 Jul 2024*

* Improvement : Improved appID check.

= 1.22.19 =
*Release Date - 21 Dec 2023*

* Improvement : The MySQL DB table 'wptc_current_process' is now changed to InnoDB engine.

* Fix : Replacing links on DB failed in rare cases during the staging process.
* Fix : Replacing links on DB failed in rare cases during the restore process.
* Fix : Handled minor PHP warning messages.

= 1.22.18 =
*Release Date - 03 Oct 2023*

* Improvement : Support for new bucket regions on S3, Wasabi and Backblaze.
* Improvement : Tested upto WP 6.3.1.

* Fix : Replacing links on DB failed in rare cases during the restore process.

= 1.22.17 =
*Release Date - 17 Aug 2023*

* Fix : PHP v8.2.0 fixes.
* Improvement : Tested upto WP 6.3.

= 1.22.16 =
*Release Date - 03 Aug 2023*

* Fix : PHP v8.2.0 fixes.

= 1.22.15 =
*Release Date - 01 Jun 2023*

* Improvement : Tested upto WP 6.2.2.

* Fix : Relative URLs on the staging site was redirected to the live site.

= 1.22.14 =
*Release Date - 15 May 2023*

* Improvement : Tested upto WP 6.2.
* Improvement : WPTC now supports upto PHP v8.2.0.

* Fix : Staging did not work with sites having the "wp-config.php" file outside the WP root directory in Gridpane and other hosting providers.

= 1.22.13 =
*Release Date - 25 Nov 2022*

* Fix : Staging did not work with sites having the "wp-config.php" file outside the WP root directory.
* Fix : Site paths on the ".htaccess" file were not replaced properly during staging when the WP Fastest Cache plugin is active.
* Fix : "Open base dir" error in a few cases.
* Fix : Elementor slider images failed to load on staging sites in a few cases.
* Fix : Restoration/Migration failed in a few rare cases.

= 1.22.12 =
*Release Date - 02 Sep 2022*

* Fix : Restoration/Migration failed in a few rare cases.

= 1.22.11 =
*Release Date - 05 Aug 2022*

* Fix : Restoration/Migration failed in a few sites having MariaDB Database.
* Fix : Child site links are not properly replaced on the staging site in a few cases.

= 1.22.10 =
*Release Date - 05 May 2022*

* Improvement : New bucket region support in Backblaze cloud storage.
* Improvement : New bucket region support Wasabi cloud storage.

= 1.22.9 =
*Release Date - 13 Apr 2022*

* Improvement : Dropbox TLS 2 support.
* Improvement : Improved Scheduled Backup failure emails.
* Improvement : Improved AWS S3 error messages.

* Fix : Critical error when WPTC automatic update is turned ON while the Yoast SEO plugin is active.
* Fix : Staging failed in a few cases.

= 1.22.8 =
*Release Date - 17 Dec 2021*

* Fix : Restoration failed when connected to Wasabi cloud storage in a few cases.
* Fix : Automatic Backup and Update failed in a few cases.

= 1.22.7 =
*Release Date - 08 Dec 2021*

* Improvement : Improved Scheduled Backup failure emails.
* Improvement : Improvements in initial setup page.

= 1.22.6 =
*Release Date - 19 Oct 2021*

* Improvement : Now Schedule backup failures will be notified to the WPTC account email.

* Fix : BackBlaze cloud storage setup failed in some cases.
* Fix : S3 cloud storage setup failed in some cases.
* Fix : Staging site gave error in some cases.

= 1.22.5 =
*Release Date - 01 Oct 2021*

* Improvement : Support for refresh tokens in Dropbox Cloud Storage.
* Improvement : et-cache folder is now excluded by default.

* Fix : Staging site failed in rare cases due to complicated htaccess file.
* Fix : Backup failed on AWS S3 US East North Virginia region in some cases.

= 1.22.4 =
*Release Date - 24 Sep 2021*

* Improvement : Backward Compatibility for sites having PHP v7.2.3 or lower.

* Fix : Critical errors on other plugins due to conflict with WPTimeCapsule new S3 SDK.
* Fix : Critical errors when the WP site is having PHP v7.2.3 or lower.

= 1.22.3 =
*Release Date - 17 Sep 2021*

* Fix : Handled fatal errors when the PHP version is < 7.2.5 and the backup is connected to S3 and S3 supported cloud storages.
* Fix : Fatal error: Undefined class constant 'MAJOR_VERSION'.

= 1.22.2 =
*Release Date - 15 Sep 2021*

* Improvement : WPTC now supports upto PHP v8.1.0.
* Improvement : Amazon S3 SDK is now updated.
* Improvement : Wasabi and Backblaze api credentials are now encoded on the WPTC plugin settings page.
* Improvement : Support for 'USWest02' bucket region is added for Backblaze Cloud Storage.
* Improvement : Support for 'AP NorthEast 1', 'US Central 1' and 'EU Central 1' bucket regions are added for Wasabi Cloud Storage.
* Improvement : Improved the handling of MyIsam DB tables.
* Improvement : Gravity Forms upload folder is not excluded by default anymore during the backups for the newly added sites.

* Fix : "Invalid handle provided" error while trying to connect S3, Wasabi or Backblaze.
* Fix : Staging failed with "Replacing links error" in some cases.
* Fix : Restore failed in some cases.
* Fix : Few DB encryption phrase characters collapsed the UI on the WPTC plugin settings page.
* Fix : "Version Mismatch" error during automatic update or manual update in some cases.

= 1.22.1 =
*Release Date - 05 Mar 2021*

* Improvement : Support for Backblaze EU regions.

* Fix : wptc_current_process DB table crashed in a few cases.

= 1.22.0 =
*Release Date - 09 Feb 2021*

* Feature: BackBlaze B2 Cloud Storage Support.

* Improvement : Added new tables in the default exclusion list.
* Improvement : Use existing Google Drive token on the second site.

* Fix : After changing the Wasabi bucket only the changed files are backed up during the first backup.
* Fix : JS fixes related to Sweetalert.
* Fix : Handled wptc_current_process table crash.

= 1.21.28 =
*Release Date - 12 Jan 2021*

* Improvement : Special On Demand backups for migration.
* Improvement : Support for South Africa AWS S3 bucket region.

* Fix : Divi builder was not working properly on WPTC staging sites.

= 1.21.27 =
*Release Date - 13 Nov 2020*

* Fix : Restore Child site failed in some cases.
* Fix : Query recorder table got big in some cases.

= 1.21.26 =
*Release Date - 22 Oct 2020*

* Fix : Minor fixes on Auto Update process.

= 1.21.25 =
*Release Date - 14 Sep 2020*

* Improvement : Support for WP version 5.5.1.

= 1.21.24 =
*Release Date - 03 Aug 2020*

* Improvement : Tested upto WP version 5.4.2.

* Fix : Exclude DB tables list failed to load.

= 1.21.23 =
*Release Date - 17 Mar 2020*

* Improvement : Added new tables in the default exclusion list.

* Fix : Files are uploaded twice in some cases.

= 1.21.22 =
*Release Date - 09 Mar 2020*

* Fix : Elementor sites' CSS collapsed after the Restore to Staging process in some cases.

= 1.21.21 =
*Release Date - 27 Feb 2020*

* Improvement : Removed .pdf files from default exclusion list.

* Fix : Elementor sites' CSS collapsed after the migration process in some cases.
* Fix : Restoring site failed when the site Database name is renamed before taking backups.

= 1.21.20 =
*Release Date - 10 Feb 2020*

* Fix : The DB table "wptc_current_process" becomes corrupted in some cases, which resulted in missing backups.

= 1.21.19 =
*Release Date - 03 Feb 2020*

* Fix : Restore stuck in "Analyzing files to restore".
* Fix : Migration failed when different wp prefix is given.
* Fix : Staging failed with "Replacing links error" in some cases.
* Fix : Wasabi backup failed when the bucket is created in US East 1 region.

= 1.21.18 =
*Release Date - 20 Jan 2020*

* Improvement : Updated Plugin description.

= 1.21.17 =
*Release Date - 14 Jan 2020*

* Fix : Google Drive duplicate files during fresh new backup.

= 1.21.16 =
*Release Date - 08 Jan 2020*

* Fix : Important security fix.

= 1.21.15 =
*Release Date - 23 Dec 2019*

* Fix : Update in Staging not working in few cases.

= 1.21.14 =
*Release Date - 13 Dec 2019*

* Fix : Wasabi US east bucket issue during restore.
* Fix : Auto update failure, in some cases.

= 1.21.13 =
*Release Date - 04 Nov 2019*

* Improvement : Major improvements in restore logic, to speed up the restore process.
* Improvement : Warning notice will be displayed, when the query recorder table gets big during realtime backup.

* Fix : Secure Auth Cookie warning is displayed in some sites, when IWP is installed.

= 1.21.12 =
*Release Date - 30 Sep 2019*

* Improvement : Excluding View tables by default during real-time backups.
* Improvement : Exclude files by size option will not allow setting less than 10MB.
* Improvement : Purple admin bar for the staging site front-end.
* Improvement : Enable admin login is now disabled by default on the staging settings.

* Fix: Rollback was not working properly when two plugins have simialr names.
* Fix: Excluding the plugins, themes update automatically after update failure was not working properly.
* Fix: DB Rows Per Batch settings save was not working properly.

= 1.21.11 =
*Release Date - 27 Aug 2019*

* Improvement : Google Drive retry mechanism improved.
* Improvement : Support for new S3 endpoints.

* Fix: IWP plugin updates were not handled properly.

= 1.21.10 =
*Release Date - 19 Aug 2019*

* Improvement : Plugin-Server communication method had been improved and security patches have been applied.

* Fix: Sweet alert modal conflict with other plugins.

= 1.21.9 =
*Release Date - 03 Jul 2019*

* Fix: Replace path error during staging in some cases.
* Fix: MainWP child site connection error, when WPTC is active.

= 1.21.8 =
*Release Date - 27 Jun 2019*

* Fix: Fatal error during WPTC plugin update.

= 1.21.7 =
*Release Date - 26 Jun 2019*

* Improvement: File iterator is optimized to take less load on the server.
* Improvement: Reduced unnecessary API calls during Google Drive and Dropbox uploads.

* Fix: DB backup failed on upper case WP prefix on windows server.
* Fix: Include/Exclude settings did not work correctly for upper case WP prefix.
* Fix: Update now button did not work as expected when a backup is already running.

= 1.21.6 =
*Release Date - 17 May 2019*

* Improvement: Plugin name is modified now.
* Improvement: Amazon and Wasabi are the recommended storage options now.

* Fix: No. of Trigger tables count check did not exclude staging tables.
* Fix: Update now button did not trigger backup, even when Always setting is ON, when not on Agency plan.
* Fix: Version mismatch error during WP core update, on multi-site installs.
* Fix: Automatic clearing of wptc_query_recorder table did not work as intended.
* Fix: Complete fix for dropbox api server load error.

= 1.21.5 =
*Release Date - 17 Apr 2019*

* Feature: Logout link on plan page.

* Improvement: After perform migration using WPTC backup, the site will be set as new.
* Improvement: Removed the replace site option, when a new site is created from clone using other plugins.
* Improvement: Support for new Wasabi endpoints.
* Improvement: Automatic resume of stopped restore process.
* Improvement: Dropbox API calls improved.
* Improvement: Automatic clearing of wptc_query_recorder table when it grows for more than a day.

* Fix: Trigger table limitation for real-time backups did not consider excluded tables.
* Fix: Update from IWP panel did not work when IWP's Client plugin branding was ON.
* Fix: Update in staging button was visible even when whitelabel staging restriction is ON.
* Fix: Backup Before Update option was not working as expected.
* Fix: JQuery file upload plugin updated.
* Fix: Migration to other site failed when sql file is more than 5MB.

= 1.21.4 =
*Release Date - 27 Mar 2019*

* Fix: Stop the Backup when Dropbox API limit is reached.

