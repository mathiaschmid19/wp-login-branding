<?php
/**
 * Helper functions for Login Branding Notice plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get plugin settings with defaults
 */
function lbn_get_settings() {
    $defaults = array(
        'custom_logo' => '',
        'background_image' => '',
        'notice_content' => '',
        'notice_start_date' => '',
        'notice_end_date' => '',
        'notice_enabled' => 0
    );
    
    $settings = get_option('lbn_settings', array());
    return wp_parse_args($settings, $defaults);
}

/**
 * Check if current date is within notice date range
 */
function lbn_is_notice_active($start_date = '', $end_date = '') {
    if (empty($start_date) && empty($end_date)) {
        return true; // No date restrictions
    }
    
    $current_date = current_time('Y-m-d');
    
    // Check start date
    if (!empty($start_date)) {
        if ($current_date < $start_date) {
            return false;
        }
    }
    
    // Check end date
    if (!empty($end_date)) {
        if ($current_date > $end_date) {
            return false;
        }
    }
    
    return true;
}

/**
 * Format date for display
 */
function lbn_format_date($date, $format = 'F j, Y') {
    if (empty($date)) {
        return __('Not set', 'wp-login-branding');
    }
    
    return date_i18n($format, strtotime($date));
}

/**
 * Get notice status text
 */
function lbn_get_notice_status() {
    $settings = lbn_get_settings();
    
    if (empty($settings['notice_enabled'])) {
        return __('Disabled', 'wp-login-branding');
    }
    
    if (empty($settings['notice_content'])) {
        return __('No content', 'wp-login-branding');
    }
    
    if (lbn_is_notice_active($settings['notice_start_date'], $settings['notice_end_date'])) {
        return __('Active', 'wp-login-branding');
    } else {
        return __('Scheduled', 'wp-login-branding');
    }
}

/**
 * Sanitize date input
 */
function lbn_sanitize_date($date) {
    if (empty($date)) {
        return '';
    }
    
    // Try to parse the date
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '';
    }
    
    return date('Y-m-d', $timestamp);
}

/**
 * Check if image URL is valid
 */
function lbn_is_valid_image_url($url) {
    if (empty($url)) {
        return false;
    }
    
    // Check if URL is valid
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    // Check if it's an image
    $image_extensions = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');
    $url_parts = parse_url($url);
    $path_info = pathinfo($url_parts['path']);
    
    return isset($path_info['extension']) && in_array(strtolower($path_info['extension']), $image_extensions);
}

/**
 * Generate admin notice
 */
function lbn_admin_notice($message, $type = 'success') {
    $class = 'notice notice-' . $type . ' is-dismissible';
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}

/**
 * Log plugin activity
 */
function lbn_log($message, $level = 'info') {
    if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
        error_log('LBN Plugin [' . strtoupper($level) . ']: ' . $message);
    }
}

/**
 * Get current user's timezone
 */
function lbn_get_user_timezone() {
    $timezone_string = get_option('timezone_string');
    
    if ($timezone_string) {
        return $timezone_string;
    }
    
    $offset = get_option('gmt_offset');
    $hours = (int) $offset;
    $minutes = abs(($offset - $hours) * 60);
    $sign = ($offset < 0) ? '-' : '+';
    
    return sprintf('%s%02d:%02d', $sign, abs($hours), $minutes);
}

/**
 * Convert date to user's timezone
 */
function lbn_convert_to_user_timezone($date, $format = 'Y-m-d H:i:s') {
    if (empty($date)) {
        return '';
    }
    
    $user_timezone = lbn_get_user_timezone();
    $datetime = new DateTime($date, new DateTimeZone('UTC'));
    $datetime->setTimezone(new DateTimeZone($user_timezone));
    
    return $datetime->format($format);
}

/**
 * Get plugin version
 */
function lbn_get_version() {
    return defined('LBN_VERSION') ? LBN_VERSION : '1.0.0';
}

/**
 * Check if plugin requirements are met
 */
function lbn_check_requirements() {
    $requirements = array(
        'php_version' => '7.4',
        'wp_version' => '5.0'
    );
    
    $errors = array();
    
    // Check PHP version
    if (version_compare(PHP_VERSION, $requirements['php_version'], '<')) {
        $errors[] = sprintf(
            __('PHP version %s or higher is required. You are running version %s.', 'wp-login-branding'),
            $requirements['php_version'],
            PHP_VERSION
        );
    }
    
    // Check WordPress version
    global $wp_version;
    if (version_compare($wp_version, $requirements['wp_version'], '<')) {
        $errors[] = sprintf(
            __('WordPress version %s or higher is required. You are running version %s.', 'wp-login-branding'),
            $requirements['wp_version'],
            $wp_version
        );
    }
    
    return empty($errors) ? true : $errors;
}

/**
 * Get plugin version
 */
function lbn_get_plugin_version() {
    return lbn_get_version();
}