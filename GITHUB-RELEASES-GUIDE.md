# GitHub Releases Setup Guide

## Overview

This plugin now supports downloading bundled plugins from GitHub Releases, reducing the main plugin size from ~109MB to ~2MB.

---

## Step-by-Step Setup

### Step 1: Prepare Plugin ZIP Files

1. **Run the build script** from the plugin root directory:
   ```
   create-plugin-zips.bat
   ```
   
2. The script will:
   - Scan `bundled-plugins/` directory
   - Create properly structured ZIP files for each plugin
   - Save them to `plugin-zips/v1.1.0/`

3. **Verify the ZIP files** are created correctly

---

### Step 2: Create GitHub Release

1. **Go to your repository**: https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/releases

2. **Click "Releases"** on the right sidebar

3. **Click "Create a new release"**

4. **Configure the release**:
   - **Choose a tag**: `v1.1.0` (create new tag)
   - **Release title**: `Plugin Bundle v1.1.0`
   - **Description**: Add release notes (optional)
   - **Set as pre-release**: Check if still testing

5. **Upload the ZIP files**:
   - Click "Attach binaries"
   - Upload ALL ZIP files from `plugin-zips/v1.1.0/`
   - Wait for all uploads to complete

6. **Click "Publish release"**

---

### Step 3: Test the Installation

1. **Upload the main plugin** (without bundled-plugins folder) to WordPress
2. **Activate** the plugin
3. **Complete the setup wizard**
4. **Verify** plugins are downloaded and installed correctly

---

## How It Works

### Plugin Flow

```
User activates plugin bundle
         ↓
Setup wizard starts
         ↓
Plugin reads config file
         ↓
Downloads ZIP from GitHub
         ↓
Extracts and installs
         ↓
Activates plugin
```

### File Structure

```
headless-wordpress-plugin-bundles/          ← Main plugin (~2MB)
├── wp-plugin-bundle.php
├── includes/
│   ├── class-plugin-installer.php          ← Has GitHub download support
│   ├── class-plugin-manager.php
│   └── plugin-download-config.php          ← Maps plugins to download URLs
└── ...

headless-wordpress-plugin-downloads (GitHub Repo)
└── Releases
    └── v1.1.0/
        ├── woocommerce.zip
        ├── wp-graphql.zip
        ├── advanced-custom-fields-pro.zip
        └── ... (all other plugins)
```

---

## Updating Plugins

### When a Plugin Gets Updated:

1. **Replace the plugin** in `bundled-plugins/` directory
2. **Run** `create-plugin-zips.bat` again
3. **Go to GitHub Releases** and edit the release
4. **Delete the old ZIP** and **upload the new one**
5. **Update** `plugin-download-config.php` if needed

---

## Configuration

### Edit Download URLs

File: `includes/plugin-download-config.php`

```php
return array(
    'woocommerce' => array(
        'zip_file' => 'woocommerce.zip',        // Must match GitHub release file name
        'version' => 'v1.1.0',                   // GitHub release tag
    ),
    // ... more plugins
);
```

### Change Repository URL

File: `includes/class-plugin-installer.php` (line 20)

```php
$this->download_url = get_option( 
    'wp_bundle_download_url', 
    'https://github.com/YOUR-USERNAME/YOUR-REPO/releases/download' 
);
```

---

## Troubleshooting

### Plugins Not Downloading

1. **Check the release URL** is correct and public
2. **Verify ZIP file names** match the config
3. **Check WordPress debug log** for errors
4. **Test download URL** in browser manually

### ZIP Extraction Fails

1. **Ensure ZIP structure** is correct (plugin folder inside ZIP)
2. **Check file permissions** on `wp-content/plugins/`
3. **Verify PHP has zip extension** enabled

### Timeout Errors

Increase timeout in `class-plugin-installer.php`:
```php
$response = wp_safe_remote_get( $url, array(
    'timeout' => 600,  // Increase from 300
    ...
) );
```

---

## Benefits

✅ **Main plugin size**: ~2MB instead of 109MB  
✅ **Easy updates**: Update individual plugins without re-uploading entire bundle  
✅ **Version control**: Track each plugin version in GitHub  
✅ **Faster deployment**: Quick upload to WordPress  
✅ **Scalable**: Add unlimited plugins without size concerns  

---

## Support

If you need help:
1. Check WordPress debug log: `wp-content/debug.log`
2. Enable WP_DEBUG in `wp-config.php`
3. Check GitHub release is accessible
4. Test download URLs manually

---

## Next Steps

- [ ] Run `create-plugin-zips.bat`
- [ ] Create GitHub Release v1.1.0
- [ ] Upload all ZIP files
- [ ] Test plugin installation
- [ ] Remove `bundled-plugins/` from main repository to reduce size
