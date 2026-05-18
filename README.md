# Headless WordPress NEXT.js Plugin Bundles

A comprehensive WordPress plugin bundle manager for headless WordPress with NEXT.js, featuring **on-demand plugin downloads** from GitHub Releases.

**Version:** 1.2.0

## Features

- ✅ **Installation Wizard** - Choose your site type before installation
- ✅ **GitHub Auto-Downloads** - Plugins downloaded automatically from GitHub Releases
- ✅ **Smart Plugin Selection** - Install only the plugins you need
- ✅ **Two Installation Modes** - Basic/Portfolio or Shop/Catalogue
- ✅ **Automatic Activation** - Plugins are automatically activated after installation
- ✅ **Admin Dashboard** - Beautiful admin interface to manage bundled plugins
- ✅ **Status Monitoring** - Track which plugins are active/inactive
- ✅ **One-Click Reinstall** - Easily reinstall all bundled plugins
- ✅ **Lightweight Plugin** - Main plugin is only ~2MB (plugins downloaded on-demand)

## How It Works

Instead of bundling all plugins in the ZIP (which would make it 60MB+), this plugin:

1. Downloads the repository source archive from GitHub Releases
2. Extracts plugins from the `bundled-plugins/` directory
3. Installs and activates only the plugins you need

**Download URL:** `https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/archive/refs/tags/{version}.zip`

## Installation

1. **Download** the plugin from [GitHub Releases](https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/releases)
2. **Upload** to your WordPress `wp-content/plugins/` directory
3. **Activate** the "Headless WordPress NEXT.js Plugin Bundles" plugin
4. **Complete the Setup Wizard** - Choose your site type:
   - **Basic/Portfolio Site**: Installs 15 plugins (excludes WooCommerce)
   - **Shop/Catalogue Site**: Installs 17 plugins (including WooCommerce)

The wizard will automatically download all plugins from GitHub! 🚀

## Included Plugins

### Basic/Portfolio Site (15 plugins):
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
- WP GraphQL Smart Cache
- WP GraphQL Tax Query

### Shop/Catalogue Site (17 plugins):
- All Basic Site plugins (15)
- **WooCommerce** - E-commerce platform
- **Gravity Forms** - Form builder

## Directory Structure

```
wp-plugin-bundle/
├── wp-plugin-bundle.php              # Main plugin file
├── includes/
│   ├── class-plugin-installer.php    # Handles plugin installation
│   ├── class-plugin-manager.php      # Manages bundled plugins
│   └── plugin-download-config.php    # GitHub download configuration
├── admin/
│   ├── class-admin.php               # Admin interface
│   └── class-installation-wizard.php # Setup wizard
├── assets/
│   ├── css/
│   │   ├── admin.css                 # Admin styles
│   │   └── wizard.css                # Wizard styles
│   └── js/
│       ├── admin.js                  # Admin JavaScript
│       └── wizard.js                 # Wizard JavaScript
├── bundled-plugins/                  #  Plugin source files (in repository)
└── README.md
```

## Changelog

### Version 1.2.0 (Current)
-  **GitHub Source Archive Downloads** - No more manual ZIP creation
- 📦 **Lightweight Plugin** - Main plugin reduced from 60MB to ~2MB
- 🔄 **On-Demand Installation** - Plugins downloaded during setup wizard
- 🎯 **Improved Error Logging** - Detailed debug output for troubleshooting
- 🔄 **Reset Wizard Feature** - Easily rerun the setup wizard
- 📝 **Updated Admin UI** - Clear instructions for GitHub workflow

### Version 1.1.0
-  **Setup Wizard UI Improvements** - Better visual design and progress indicators
- 🔄 **Plugin Installation Progress** - Real-time status updates during installation
- 🛠️ **Admin Dashboard Enhancements** - Better plugin management interface
- 📋 **Site Type Selection** - Choose between Basic/Portfolio or Shop/Catalogue
- 🔧 **Bug Fixes** - Various stability improvements

### Version 1.0.0
- ✨ **Initial Release**
- 🎯 **Installation Wizard** - Guided setup process
-  **Plugin Bundling** - Package multiple plugins together
- 🔄 **Automatic Activation** - One-click plugin activation
- 📊 **Admin Dashboard** - Manage bundled plugins

## Development

### Adding New Plugins

1. Add plugin folder to `bundled-plugins/` directory
2. Update `includes/plugin-download-config.php` with plugin details
3. Commit changes and create new GitHub tag
4. Update version in `wp-plugin-bundle.php`

### Configuration

Edit `includes/plugin-download-config.php` to customize:
- Plugin slugs and names
- Download URLs
- Version tags

## Support

- **GitHub Repository:** https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles
- **Issues:** https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/issues
- **Author:** High6-Gio
- **Website:** https://high6.com/

## License

GPL v2 or later

---

**Built for headless WordPress with NEXT.js** 
