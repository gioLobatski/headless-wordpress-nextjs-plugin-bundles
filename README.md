# Headless WordPress NEXT.js Plugin Bundles

A comprehensive WordPress plugin bundle manager for headless WordPress with NEXT.js, that allows you to package, install, and activate multiple plugins as a single unit.

## Features

- ✅ **Installation Wizard** - Choose your site type before installation
- ✅ **Smart Plugin Selection** - Install only the plugins you need
- ✅ **Two Installation Modes** - Basic/Portfolio or Shop/Catalogue
- ✅ **Automatic Activation** - Plugins are automatically activated after selection
- ✅ **Admin Dashboard** - Beautiful admin interface to manage bundled plugins
- ✅ **Status Monitoring** - Track which plugins are active/inactive
- ✅ **One-Click Reinstall** - Easily reinstall all bundled plugins
- ✅ **Extensible** - Easy to add or remove plugins from the bundle

## Directory Structure

```
wp-plugin-bundle/
├── wp-plugin-bundle.php          # Main plugin file
├── includes/
│   ├── class-plugin-installer.php    # Handles plugin installation
│   └── class-plugin-manager.php      # Manages bundled plugins
├── admin/
│   └── class-admin.php               # Admin interface
├── assets/
│   ├── css/
│   │   └── admin.css                 # Admin styles
│   └── js/
│       └── admin.js                  # Admin JavaScript
├── bundled-plugins/                  # 📦 Place your plugins here
└── README.md
```

## Installation

1. **Download** the entire `wp-plugin-bundle` folder
2. **Upload** to your WordPress `wp-content/plugins/` directory
3. **Add your plugins** to the `bundled-plugins/` folder (see below)
4. **Activate** the "WordPress Plugin Bundle" plugin from WordPress admin
5. **Complete the Setup Wizard** - Choose your site type:
   - **Basic/Portfolio Site**: Installs 15 plugins (excludes WooCommerce)
   - **Shop/Catalogue Site**: Installs all 17 plugins (including WooCommerce)

## How to Add Plugins to the Bundle

### Method 1: Single File Plugins

If your plugin is a single PHP file (e.g., `my-plugin.php`):

1. Copy the plugin file to `bundled-plugins/` directory
2. That's it! The plugin will be automatically detected

```
bundled-plugins/
└── my-plugin.php
```

### Method 2: Multi-File Plugins (Directories)

If your plugin consists of multiple files in a directory:

1. Copy the entire plugin directory to `bundled-plugins/`
2. Ensure the main plugin file has the proper WordPress plugin header
3. The bundle will detect and install it automatically

```
bundled-plugins/
├── woocommerce/
│   ├── woocommerce.php
│   ├── includes/
│   └── assets/
└── yoast-seo/
    ├── wp-seo.php
    └── ...
```

### Method 3: Download from WordPress Repository

You can also download plugins from WordPress.org and add them:

```bash
# Example: Download and add WooCommerce
cd /path/to/wp-plugin-bundle/bundled-plugins
wget https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip
unzip woocommerce.latest.stable.zip
rm woocommerce.latest.stable.zip
```

## Usage

### Installation Wizard (First Time Setup)

When you activate the plugin bundle for the first time, you'll be redirected to the **Setup Wizard**:

#### Option 1: Basic or Portfolio Site
**Perfect for:** Blogs, business sites, portfolios, brochures

**Installs 15 plugins:**
- Advanced Custom Fields Pro
- Classic Editor
- Duplicate Page
- iThemes Security Pro
- Imagify (Image Optimization)
- Rank Math SEO
- SVG Support
- InfiniteWP Client
- WP Time Capsule (Backup)
- WP GraphQL
- WP GraphQL ACF
- WP GraphQL IDE
- WP GraphQL Rank Math
- WP GraphQL Smart Cache
- WP GraphQL Tax Query

**Excludes:** WooCommerce, WPGraphQL WooCommerce

#### Option 2: Shop or Catalogue Site
**Perfect for:** E-commerce stores, product catalogues

**Installs all 17 plugins:**
- All 15 plugins from Basic/Portfolio
- WooCommerce
- WPGraphQL WooCommerce

### After Wizard Completion

Once you complete the wizard:
- Navigate to **Plugin Bundle** in your WordPress admin menu to see the dashboard
- Monitor plugin status
- Reinstall plugins if needed

### Admin Dashboard

The admin dashboard provides:

- **Statistics**: View total, active, and inactive plugin counts
- **Plugin List**: See all bundled plugins with their status
- **Reinstall All**: One-click button to reinstall all bundled plugins
- **Refresh Status**: Update the plugin status display
- **Quick Actions**: Activate individual plugins directly from the dashboard

### Managing Plugins

#### Adding New Plugins

1. Place the plugin file or directory in `bundled-plugins/`
2. Go to **Plugin Bundle** admin page
3. Click **Reinstall All Plugins** to install the new additions

#### Removing Plugins

1. Simply delete or move the plugin out of the `bundled-plugins/` directory
2. You may want to manually deactivate and delete it from WordPress Plugins page

#### Updating Plugins

1. Replace the old plugin files in `bundled-plugins/` with the new version
2. Click **Reinstall All Plugins** from the admin dashboard

## Technical Details

### How It Works

1. **Detection**: The plugin scans the `bundled-plugins/` directory on activation
2. **Installation**: Plugins are copied to WordPress's `wp-content/plugins/` directory
3. **Activation**: Each plugin is programmatically activated
4. **Management**: Admin interface provides ongoing management capabilities

### WordPress Functions Used

- `activate_plugin()` - Activates plugins programmatically
- `get_plugin_data()` - Reads plugin headers
- `is_plugin_active()` - Checks plugin activation status
- `WP_Filesystem` - Safely copies files in WordPress environment

### Hooks and Filters

The plugin uses standard WordPress hooks:

- `register_activation_hook` - Triggered on bundle activation
- `register_deactivation_hook` - Triggered on bundle deactivation
- `plugins_loaded` - Initializes the plugin
- `admin_init` - Checks bundled plugins status
- `admin_menu` - Adds admin menu item
- `admin_enqueue_scripts` - Loads admin assets

## Customization

### Changing the Menu Position

Edit `admin/class-admin.php` and modify the `add_menu_page()` priority:

```php
add_menu_page(
    ...,
    'dashicons-admin-plugins',
    100  // Change this number to adjust position
);
```

### Auto-Activate on Bundle Activation

This is already enabled by default. To disable, edit `wp-plugin-bundle.php`:

```php
public function activate() {
    // Comment out this line to disable auto-installation
    // $this->install_bundled_plugins();
}
```

## Troubleshooting

### Plugins Not Installing

**Problem**: Bundled plugins are not being installed on activation.

**Solution**:
1. Check file permissions on `wp-content/plugins/` directory
2. Ensure plugins in `bundled-plugins/` have valid plugin headers
3. Check WordPress debug log for errors

### Plugin Headers Required

Each plugin must have this header in its main PHP file:

```php
<?php
/**
 * Plugin Name: My Plugin
 * Version: 1.0.0
 */
```

### Permissions Issues

Ensure WordPress has write access to the plugins directory:

```bash
chmod 755 wp-content/plugins/
```

## Best Practices

1. **Test First**: Test the bundle on a staging site before production
2. **Backup**: Always backup before installing multiple plugins
3. **Version Control**: Keep track of plugin versions in the bundle
4. **Documentation**: Maintain a list of included plugins and their versions
5. **Updates**: Regularly update bundled plugins for security

## Included Plugins

*Add your list of bundled plugins here:*

| Plugin Name | Version | Description |
|------------|---------|-------------|
| [Plugin 1] | 1.0.0   | Description |
| [Plugin 2] | 2.0.0   | Description |

## License

GPL v2 or later

## Support

For support, please contact: your-email@yourwebsite.com

## Changelog

### 1.0.0
- Initial release
- Plugin bundle management system
- Admin dashboard
- Automatic installation and activation
