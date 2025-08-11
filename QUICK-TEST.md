# Quick Test Guide - Bug Fix Verification

## 🐛 Issue Fixed
**Problem:** `Fatal error: Call to undefined function lbn_check_requirements()`

**Solution:** Moved the `includes()` call before the requirements check in the main plugin file.

## ✅ How to Test the Fix

### 1. Upload Plugin to WordPress
1. Copy the entire `wp-login-branding` folder to your WordPress `/wp-content/plugins/` directory
2. Or create a ZIP file and upload via WordPress admin

### 2. Activate Plugin
1. Go to **Plugins** in your WordPress admin
2. Find "Login Screen Branding & Notice"
3. Click **Activate**

### 3. Expected Results
✅ **Plugin should activate without errors**
✅ **No fatal errors should appear**
✅ **Settings page should be accessible at Settings > Login Branding & Notice**

### 4. If Activation Succeeds
1. Go to **Settings > Login Branding & Notice**
2. You should see the admin interface
3. Try uploading a logo or background image
4. Create a test notice
5. Check the login page to see your changes

## 🔧 What Was Changed

### Before (Causing Error)
```php
public function init() {
    // Check requirements
    if (!lbn_check_requirements()) {  // ❌ Function not loaded yet
        return;
    }
    
    // Include required files
    $this->includes();
    // ...
}
```

### After (Fixed)
```php
public function init() {
    // Include required files first
    $this->includes();  // ✅ Load functions first
    
    // Check requirements
    if (!lbn_check_requirements()) {  // ✅ Function now available
        return;
    }
    // ...
}
```

## 🚨 If You Still Get Errors

### Check These Common Issues:

1. **File Permissions**
   - Ensure WordPress can read the plugin files
   - Check folder permissions (755) and file permissions (644)

2. **WordPress Version**
   - Plugin requires WordPress 5.0+
   - Check your WordPress version

3. **PHP Version**
   - Plugin requires PHP 7.4+
   - Check your PHP version

4. **Plugin Conflicts**
   - Temporarily deactivate other plugins
   - Test if the issue persists

5. **Debug Information**
   - Enable WordPress debug mode in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```
   - Check `/wp-content/debug.log` for detailed error messages

## 📞 Support

If you continue to experience issues:

1. **Check the error logs** for specific error messages
2. **Verify file structure** matches the expected layout
3. **Test in a clean WordPress installation** to rule out conflicts
4. **Review the INSTALLATION.md** guide for detailed setup instructions

## ✨ Success Indicators

When everything is working correctly, you should see:

- ✅ Plugin activates without errors
- ✅ Admin menu item appears under Settings
- ✅ Settings page loads properly
- ✅ Media uploader works for logo/background
- ✅ Notice editor functions correctly
- ✅ Login page shows your customizations

---

**Version:** 1.0.1 (Bug Fix Release)
**Date:** January 2024