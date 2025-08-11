# IDE Setup Guide for WordPress Plugin Development

## 🚨 Resolving "Undefined function" Errors

If you're seeing errors like "Undefined function 'add_action'" in your IDE (especially with Intelephense), this is normal when developing WordPress plugins outside of a WordPress environment.

## 🛠️ VS Code + Intelephense Setup

### Method 1: Use Provided Configuration (Recommended)

This plugin includes VS Code configuration files that should automatically resolve most issues:

1. **Files Included:**
   - `.vscode/settings.json` - Intelephense configuration
   - `.vscode/wordpress-stubs.php` - WordPress function stubs
   - `.vscode/extensions.json` - Recommended extensions

2. **Restart VS Code** after opening the plugin folder to apply settings.

### Method 2: Manual Configuration

If you prefer to configure manually:

1. **Install Intelephense Extension:**
   ```
   ext install bmewburn.vscode-intelephense-client
   ```

2. **Add to VS Code Settings (settings.json):**
   ```json
   {
     "intelephense.stubs": ["wordpress"],
     "intelephense.diagnostics.undefinedFunctions": false
   }
   ```

### Method 3: WordPress Development Environment

For the most accurate IDE support, develop within a WordPress installation:

1. **Local WordPress Setup:**
   - Use Local by Flywheel, XAMPP, or Docker
   - Place plugin in `/wp-content/plugins/`
   - Open the WordPress root directory in VS Code

2. **WordPress Stubs Package:**
   ```bash
   composer require --dev php-stubs/wordpress-stubs
   ```

## 🔧 Alternative IDEs

### PhpStorm
1. Install WordPress plugin
2. Enable WordPress support in settings
3. Point to WordPress installation or stubs

### Sublime Text
1. Install LSP package
2. Configure Intelephense LSP
3. Use WordPress stubs

### Vim/Neovim
1. Install coc.nvim or similar LSP client
2. Configure Intelephense
3. Use WordPress stubs

## 📝 Understanding the Errors

### Why These Errors Occur
- WordPress functions are not available outside WordPress environment
- IDEs can't find function definitions
- This is a development-time issue, not a runtime issue

### Common "Undefined" Functions
- `add_action()`, `add_filter()`
- `wp_enqueue_script()`, `wp_enqueue_style()`
- `get_option()`, `update_option()`
- `current_user_can()`
- `__()`, `_e()` (translation functions)

### These Are NOT Real Errors
- The plugin will work correctly in WordPress
- Functions are defined by WordPress core
- IDE warnings can be safely ignored

## ✅ Verification

### Test Plugin Functionality
1. **Use Verification Script:**
   ```php
   // Run verify-plugin.php to check file structure
   ```

2. **Install in WordPress:**
   - Upload to `/wp-content/plugins/`
   - Activate and test functionality

3. **Check for Real Errors:**
   - Enable WordPress debug mode
   - Check error logs for actual issues

### Expected Behavior
- ✅ Plugin activates without errors
- ✅ Admin settings page loads
- ✅ Frontend modifications work
- ✅ No PHP fatal errors

## 🎯 Best Practices

### Development Workflow
1. **Develop with IDE warnings** (they're not real errors)
2. **Test in actual WordPress** environment regularly
3. **Use verification tools** provided with plugin
4. **Enable WordPress debug** during development

### Code Quality
- Follow WordPress coding standards
- Use proper escaping and sanitization
- Include proper documentation
- Test across different WordPress versions

## 🔍 Troubleshooting

### Still Seeing Errors?

1. **Check VS Code Settings:**
   - Ensure `.vscode/settings.json` is loaded
   - Restart VS Code completely
   - Check Intelephense extension is active

2. **Verify File Structure:**
   ```
   wp-login-branding/
   ├── .vscode/
   │   ├── settings.json
   │   ├── wordpress-stubs.php
   │   └── extensions.json
   ├── includes/
   ├── assets/
   └── wp-login-notice.php
   ```

3. **Alternative Solutions:**
   - Disable specific Intelephense diagnostics
   - Use `@phpstan-ignore-line` comments
   - Develop within WordPress environment

### Real vs. False Errors

**False Errors (Safe to Ignore):**
- "Undefined function 'add_action'"
- "Undefined function 'wp_enqueue_script'"
- "Undefined constant 'ABSPATH'"

**Real Errors (Need Fixing):**
- Syntax errors (missing semicolons, brackets)
- Undefined custom functions
- Incorrect function calls

## 📚 Additional Resources

### WordPress Development
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Function Reference](https://developer.wordpress.org/reference/)

### IDE Configuration
- [Intelephense Documentation](https://intelephense.com/)
- [VS Code PHP Development](https://code.visualstudio.com/docs/languages/php)
- [WordPress Stubs Repository](https://github.com/php-stubs/wordpress-stubs)

---

**Remember:** IDE warnings about WordPress functions are normal and expected when developing outside of WordPress. The plugin will work correctly when installed in WordPress!