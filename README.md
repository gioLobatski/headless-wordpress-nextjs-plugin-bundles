# Headless WordPress NEXT.js Plugin Bundles

A comprehensive WordPress plugin bundle manager for headless WordPress with NEXT.js, featuring **on-demand plugin downloads** from GitHub Releases.

**Version:** 1.3.0

## Features

- ✅ **Installation Wizard** - Choose your site type before installation
- ✅ **GitHub Auto-Downloads** - Plugins downloaded individually from GitHub Releases
- ✅ **Smart Plugin Selection** - Install only the plugins you need
- ✅ **Two Installation Modes** - Basic/Portfolio or Shop/Catalogue
- ✅ **Automatic Activation** - Plugins are automatically activated after installation
- ✅ **Admin Dashboard** - Beautiful admin interface to manage bundled plugins
- ✅ **Status Monitoring** - Track which plugins are active/inactive
- ✅ **One-Click Reinstall** - Easily reinstall all bundled plugins
- ✅ **Lightweight Plugin** - Main plugin is only ~2MB (plugins downloaded on-demand)

## How It Works

Instead of bundling all plugins inside the main plugin ZIP (which would make it 100MB+), each plugin is uploaded as an **individual ZIP asset** to a GitHub Release. The plugin downloads only the ZIPs it needs, one at a time:

1. The wizard reads `includes/plugin-download-config.php` to know which ZIPs to fetch
2. Each plugin ZIP is downloaded directly from the GitHub Release as an asset
3. WordPress extracts the ZIP and activates the plugin

**Asset URL format:**
```
https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/releases/download/{version}/{plugin-slug}.zip
```

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

### Version 1.3.0 (Current)
- **Individual Plugin ZIPs** - Reverted to per-plugin ZIP downloads from GitHub Release assets (more reliable than source archives)
- **Build Script** - New `create-plugin-zips.ps1` PowerShell script that auto-packages every folder in `bundled-plugins/` into a properly structured ZIP
- **Cleaner Installer** - Removed the source-archive code path; one ZIP per plugin keeps installation fast and easy to debug

### Version 1.2.0
- GitHub source archive downloads (deprecated in 1.3.0)
- Lightweight plugin - Main plugin reduced from 60MB to ~2MB
- On-demand installation - Plugins downloaded during setup wizard
- Improved error logging - Detailed debug output for troubleshooting
- Reset wizard feature
- Updated admin UI

### Version 1.1.0
- Setup wizard UI improvements
- Plugin installation progress
- Admin dashboard enhancements
- Site type selection
- Bug fixes

### Version 1.0.0
- Initial release

## Development

### Releasing a New Version

1. Add or update plugin folders inside `bundled-plugins/` (this folder is gitignored — it's a local working area)
2. Run the build script to package every folder into a properly structured ZIP:
   ```powershell
   .\create-plugin-zips.ps1 -Version v1.3.0
   ```
   The output is written to `plugin-zips/v1.3.0/` (one `<slug>.zip` per plugin).
3. Create a new GitHub release with tag `v1.3.0` and upload **every ZIP** from the output folder as release assets.
4. Update `includes/plugin-download-config.php` so each entry's `version` matches the new tag.
5. Bump `Version:` and `WP_BUNDLE_VERSION` in `wp-plugin-bundle.php`.

### Adding a New Plugin

1. Drop the plugin folder into `bundled-plugins/`
2. Add an entry to the `$PluginMap` in `create-plugin-zips.ps1`
3. Add an entry to `includes/plugin-download-config.php`
4. Re-run the build script and re-upload the new ZIP to the release

## Support

- **GitHub Repository:** https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles
- **Issues:** https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/issues
- **Author:** High6-Gio
- **Website:** https://high6.com/

## License

GPL v2 or later

---

**Built for headless WordPress with NEXT.js** 
