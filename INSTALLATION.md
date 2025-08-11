# Installation Guide - Login Screen Branding & Notice Plugin

## 📋 Prerequisites

Before installing this plugin, ensure your WordPress site meets the following requirements:

- **WordPress Version:** 5.0 or higher
- **PHP Version:** 7.4 or higher
- **User Permissions:** Administrator access to install plugins
- **Server Requirements:** Standard WordPress hosting environment

## 🚀 Installation Methods

### Method 1: Manual Installation (Recommended)

1. **Download the Plugin**
   - Download or clone this repository
   - Ensure all files are present (use `verify-plugin.php` to check)

2. **Upload to WordPress**
   ```
   /wp-content/plugins/wp-login-branding/
   ```
   - Upload the entire `wp-login-branding` folder to your WordPress plugins directory
   - Ensure file permissions are set correctly (644 for files, 755 for directories)

3. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "Login Screen Branding & Notice"
   - Click "Activate"

### Method 2: ZIP Upload

1. **Create ZIP Archive**
   - Compress the entire plugin folder into `wp-login-branding.zip`
   - Ensure the main plugin file is in the root of the ZIP

2. **Upload via WordPress Admin**
   - Go to WordPress Admin → Plugins → Add New
   - Click "Upload Plugin"
   - Choose your ZIP file and click "Install Now"
   - Activate the plugin after installation

## ⚙️ Initial Configuration

### 1. Access Plugin Settings

After activation, navigate to:
```
WordPress Admin → Settings → Login Branding
```

### 2. Configure Branding Options

**Custom Logo:**
- Upload your company/site logo
- Recommended size: 320x80 pixels (or proportional)
- Supported formats: JPG, PNG, SVG, WebP

**Background Image:**
- Upload a background image for the login page
- Recommended size: 1920x1080 pixels or higher
- The image will be automatically scaled and positioned

### 3. Set Up Login Notices

**Enable Notices:**
- Check "Enable Notice" to activate the notice system
- Configure start and end dates for scheduled notices
- Use the rich text editor to create your notice content

**Notice Content:**
- Supports HTML formatting
- Can include links, images, and basic styling
- Preview functionality available

## 🔧 Advanced Configuration

### Custom CSS Styling

The plugin includes default styling, but you can customize it further:

1. **Theme Integration:**
   - Add custom CSS to your theme's `style.css`
   - Target classes like `.lbn-notice`, `.lbn-login-form`

2. **Plugin CSS Override:**
   - Modify `assets/css/login-styles.css` (not recommended)
   - Better: Use theme CSS or custom CSS plugin

### Hooks and Filters

The plugin provides several hooks for developers:

```php
// Modify notice content
add_filter('lbn_notice_content', 'your_custom_function');

// Customize logo CSS
add_filter('lbn_logo_css', 'your_logo_css_function');

// Modify background CSS
add_filter('lbn_background_css', 'your_background_css_function');
```

## 🛠️ Troubleshooting

### Common Issues

**Plugin Not Appearing:**
- Check file permissions (644 for files, 755 for directories)
- Ensure all required files are present
- Check PHP error logs for syntax errors

**Images Not Loading:**
- Verify image URLs are accessible
- Check WordPress media library permissions
- Ensure images are in supported formats

**Notices Not Displaying:**
- Check if notices are enabled
- Verify date ranges are correct
- Ensure notice content is not empty

**Styling Issues:**
- Clear browser cache
- Check for theme CSS conflicts
- Verify CSS files are loading correctly

### Debug Mode

Enable WordPress debug mode to troubleshoot issues:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check debug logs in `/wp-content/debug.log`

### Plugin Verification

Run the verification script to check plugin integrity:

1. Access `verify-plugin.php` directly in your browser
2. Or add `?lbn_verify=1` to any admin page URL
3. Review the results for any missing files or issues

## 🔒 Security Considerations

### File Permissions

Set appropriate file permissions:
```bash
find /path/to/plugin -type f -exec chmod 644 {} \;
find /path/to/plugin -type d -exec chmod 755 {} \;
```

### Content Security

- The plugin sanitizes all user input
- HTML content is filtered for security
- File uploads are restricted to images only
- Nonce verification is used for all AJAX requests

### Regular Updates

- Keep the plugin updated
- Monitor for security advisories
- Backup your site before updates

## 📱 Mobile Responsiveness

The plugin is designed to work on all devices:

- **Desktop:** Full functionality with sidebar layout
- **Tablet:** Responsive design adapts to screen size
- **Mobile:** Touch-friendly interface with optimized layout

## 🌐 Internationalization

### Language Support

The plugin is translation-ready:

1. **POT File:** `languages/wp-login-branding.pot`
2. **Text Domain:** `wp-login-branding`
3. **Translation Files:** Place in `languages/` directory

### Creating Translations

1. Use the provided POT file as a template
2. Create language-specific PO files
3. Compile to MO files for production use

Example for Spanish:
- `wp-login-branding-es_ES.po`
- `wp-login-branding-es_ES.mo`

## 📞 Support

### Getting Help

1. **Documentation:** Check this guide and README.md
2. **Verification:** Run the plugin verification script
3. **Debug Logs:** Enable WordPress debug mode
4. **Community:** Check WordPress forums and documentation

### Reporting Issues

When reporting issues, please include:
- WordPress version
- PHP version
- Plugin version
- Error messages (if any)
- Steps to reproduce the issue

## 🔄 Uninstallation

### Clean Removal

The plugin includes an uninstall script that:
- Removes all plugin options
- Cleans up scheduled events
- Removes cached data

### Manual Cleanup

If needed, manually remove:
```sql
DELETE FROM wp_options WHERE option_name LIKE 'lbn_%';
```

## ✅ Post-Installation Checklist

- [ ] Plugin activated successfully
- [ ] Settings page accessible
- [ ] Custom logo uploads and displays correctly
- [ ] Background image uploads and displays correctly
- [ ] Notice system functions properly
- [ ] Preview functionality works
- [ ] Mobile responsiveness verified
- [ ] No JavaScript errors in browser console
- [ ] No PHP errors in debug logs

## 🎯 Next Steps

After successful installation:

1. **Customize Branding:** Upload your logo and background
2. **Create Notices:** Set up important announcements
3. **Test Functionality:** Use preview features to verify appearance
4. **Monitor Performance:** Check for any conflicts with other plugins
5. **Plan Maintenance:** Schedule regular updates and backups

---

**Need additional help?** Refer to the main README.md file for detailed feature documentation and usage examples.