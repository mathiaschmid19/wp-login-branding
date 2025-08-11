# Login Screen Branding & Notice

A comprehensive WordPress plugin that allows you to customize your login screen with custom branding and display scheduled notices using a rich text editor.

## 🚀 Features

### Branding Customization
- **Custom Logo**: Upload and display a custom logo on the login page
- **Background Image**: Set a custom background image for the login screen
- **Form Styling**: Customize login form appearance with background colors, opacity, borders, and shadows
- **Button Styling**: Complete button customization with colors, hover effects, typography, and borders
- **Responsive Design**: Automatically adapts to different screen sizes
- **Preview Functionality**: Live preview of changes before saving

### Notice Management
- **Rich Text Editor**: Full WYSIWYG editor with media upload support
- **Scheduled Display**: Set start and end dates for notice visibility
- **Enable/Disable**: Easy toggle to turn notices on or off
- **Live Preview**: Preview notices directly in the admin interface
- **Dismissible Notices**: Users can dismiss notices (optional)

### Admin Interface
- **Modern UI**: Clean, professional admin interface
- **Sidebar Layout**: Organized layout with quick actions and plugin info
- **Form Validation**: Real-time validation with user-friendly error messages
- **Media Integration**: WordPress media library integration for uploads

### Technical Features
- **Security**: Proper sanitization, nonce verification, and capability checks
- **Performance**: Minimal impact on site performance
- **Internationalization**: Translation-ready with POT file included
- **Standards Compliance**: Follows WordPress coding standards
- **Clean Uninstall**: Properly removes all data when uninstalled

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **Permissions**: Administrator access for configuration

## 🛠️ Development Setup

If you're developing or modifying this plugin and seeing IDE errors like "Undefined function 'add_action'", please see the **[IDE Setup Guide](IDE-SETUP.md)** for configuration instructions. These are false warnings that occur when developing WordPress plugins outside of a WordPress environment.

## 🔧 Installation

### Method 1: Upload Plugin Files
1. Download the plugin files
2. Upload the `wp-login-branding` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to Settings > Login Branding & Notice to configure

### Method 2: WordPress Admin Upload
1. Go to Plugins > Add New in your WordPress admin
2. Click "Upload Plugin"
3. Choose the plugin ZIP file and click "Install Now"
4. Activate the plugin
5. Configure via Settings > Login Branding & Notice

## ⚙️ Configuration

### Branding Settings

#### Custom Logo
1. Navigate to Settings > Login Branding & Notice
2. In the "Custom Logo" field, click "Upload Logo"
3. Select an image from your media library or upload a new one
4. Recommended size: 320x80 pixels or similar aspect ratio
5. Click "Save Changes"

#### Background Image
1. In the "Background Image" field, click "Upload Background"
2. Select a background image from your media library
3. Recommended size: 1920x1080 pixels or higher for best quality
4. The image will automatically scale and position correctly
5. Click "Save Changes"

### Notice Settings

#### Creating a Notice
1. Check "Enable Notice" to activate notice functionality
2. Use the rich text editor to create your notice content
3. You can add:
   - Formatted text (bold, italic, colors)
   - Links
   - Images
   - Lists
   - Custom HTML (if needed)

#### Scheduling Notices
1. **Start Date**: Set when the notice should begin displaying
2. **End Date**: Set when the notice should stop displaying
3. **Format**: Use YYYY-MM-DD format (e.g., 2024-12-25)
4. **Permanent Display**: Leave dates empty to show the notice always (when enabled)

#### Preview Notice
- Click "Preview Notice" to see how your notice will appear
- The preview shows the exact formatting and styling
- Make adjustments as needed before saving

### Form & Button Styling

#### Form Styling Options
1. Navigate to the "Form Styling" tab in Settings > Login Branding & Notice
2. Customize the login form appearance:
   - **Background Color**: Set the form background color using hex codes
   - **Background Opacity**: Adjust transparency (0-100%)
   - **Border Radius**: Round the form corners (0-50px)
   - **Box Shadow**: Add depth with shadow effects
   - **Text Color**: Change form text color
   - **Font Settings**: Customize font family, size, and weight

#### Button Styling Options
1. In the "Form Styling" tab, scroll to the "Button Styling" section
2. Customize login button appearance:
   - **Background Color**: Set button background color (hex format)
   - **Text Color**: Change button text color
   - **Hover Background**: Set background color on hover
   - **Hover Text Color**: Change text color on hover
   - **Border Radius**: Round button corners (0-50px)
   - **Font Size**: Adjust button text size (8-32px)
   - **Font Weight**: Set text weight (normal, bold, etc.)

#### Color Format
- Use hex color codes (e.g., #0073aa, #ffffff)
- Color picker is available for easy selection
- Real-time validation ensures proper color format

## 🎨 Customization

### CSS Customization
The plugin includes comprehensive CSS that can be customized:

```css
/* Custom logo styling */
.login h1 a {
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

/* Login form styling */
.login form {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 26px 24px 46px;
}

/* Button styling */
.login form input[type="submit"] {
    background-color: #0073aa;
    color: #ffffff;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.login form input[type="submit"]:hover {
    background-color: #005a87;
    color: #ffffff;
}

/* Notice styling */
.lbn-notice {
    background: #f0f6fc;
    border: 1px solid #0073aa;
    border-radius: 3px;
    padding: 15px;
    margin: 15px 0;
}
```

### Hooks and Filters

#### Available Filters
```php
// Modify notice content before display
add_filter('lbn_notice_content', function($content) {
    return $content . '<p>Additional text</p>';
});

// Modify login logo URL
add_filter('login_headerurl', function($url) {
    return home_url();
});

// Modify login logo title
add_filter('login_headertext', function($text) {
    return get_bloginfo('name');
});
```

#### Available Actions
```php
// Before notice display
add_action('lbn_before_notice', function() {
    // Custom code here
});

// After notice display
add_action('lbn_after_notice', function() {
    // Custom code here
});
```

## 🔒 Security Features

- **Input Sanitization**: All user inputs are properly sanitized
- **Nonce Verification**: All forms use WordPress nonces
- **Capability Checks**: Only administrators can modify settings
- **SQL Injection Prevention**: Uses WordPress database functions
- **XSS Protection**: Output is properly escaped

## 🐛 Troubleshooting

### Common Issues

#### Logo Not Displaying
1. Check that the image URL is correct and accessible
2. Verify the image file exists and has proper permissions
3. Try uploading a different image format (JPG, PNG, GIF)
4. Clear any caching plugins

#### Background Image Not Showing
1. Ensure the image is large enough (minimum 1200px wide)
2. Check browser developer tools for CSS errors
3. Verify the image URL is accessible
4. Try a different image format

#### Notice Not Appearing
1. Verify "Enable Notice" is checked
2. Check that current date is within the start/end date range
3. Ensure notice content is not empty
4. Clear any caching plugins

#### Admin Interface Issues
1. Check that JavaScript is enabled in your browser
2. Verify you have administrator privileges
3. Try disabling other plugins temporarily
4. Check browser console for JavaScript errors

#### Button/Form Styling Not Applying
1. Clear browser cache and any caching plugins
2. Check that color values are in proper hex format (#ffffff)
3. Verify numeric values are within allowed ranges
4. Ensure no theme CSS is overriding the styles with higher specificity
5. Check browser developer tools for CSS conflicts

#### Color Picker Not Working
1. Ensure JavaScript is enabled
2. Check for JavaScript errors in browser console
3. Try refreshing the admin page
4. Verify browser compatibility (modern browsers required)

### Debug Mode
To enable debug logging, add this to your `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Debug messages will appear in `/wp-content/debug.log`.

## 📁 File Structure

```
wp-login-branding/
├── wp-login-notice.php          # Main plugin file
├── uninstall.php               # Uninstall cleanup
├── README.md                   # Documentation
├── CHANGELOG.md               # Version history
├── assets/
│   ├── admin-styles.css       # Admin interface styles
│   ├── admin-scripts.js       # Admin JavaScript
│   └── login-styles.css       # Login page styles
├── includes/
│   ├── class-admin.php        # Admin functionality
│   ├── class-frontend.php     # Frontend functionality
│   ├── functions.php          # Core functions
│   └── helpers.php            # Helper functions
├── languages/
│   └── login-branding-notice.pot  # Translation template
└── test-plugin.php            # Testing utilities
```

## 🌐 Internationalization

The plugin is translation-ready. To create translations:

1. Use the provided POT file: `/languages/login-branding-notice.pot`
2. Create PO files for your language (e.g., `login-branding-notice-es_ES.po`)
3. Compile to MO files
4. Place in the `/languages/` directory

## 🔄 Updates and Maintenance

### Best Practices
1. **Backup**: Always backup your site before updating
2. **Testing**: Test changes on a staging site first
3. **Caching**: Clear caches after making changes
4. **Monitoring**: Check error logs regularly

### Performance Tips
1. Use optimized images (WebP format when possible)
2. Keep notice content concise
3. Use appropriate image sizes
4. Enable caching plugins

## 📞 Support

### Getting Help
1. Check this documentation first
2. Review the troubleshooting section
3. Check WordPress error logs
4. Test with default theme and no other plugins

### Reporting Issues
When reporting issues, please include:
- WordPress version
- PHP version
- Plugin version
- Error messages (if any)
- Steps to reproduce the issue

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🙏 Credits

Developed with ❤️ for the WordPress community.

---

**Version**: 1.0.0  
**Tested up to**: WordPress 6.4  
**Requires PHP**: 7.4+