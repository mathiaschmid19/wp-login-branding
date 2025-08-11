<?php
/**
 * Uninstall Login Branding Notice
 *
 * @package LoginBrandingNotice
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('lbn_settings');

// Delete any transients
delete_transient('lbn_notice_cache');

// Clear any scheduled events
wp_clear_scheduled_hook('lbn_cleanup_logs');

// Remove any custom capabilities (if any were added)
// This plugin doesn't add custom capabilities, but this is where you would remove them

// Clean up any custom database tables (if any were created)
// This plugin doesn't create custom tables, but this is where you would drop them

// Remove any uploaded files in the plugin directory (if any)
// This plugin doesn't create files, but this is where you would clean them up

// Log the uninstall (optional)
if (function_exists('error_log')) {
    error_log('Login Branding Notice plugin has been uninstalled.');
}