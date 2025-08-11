<?php
/**
 * Additional helper functions for Login Branding Notice plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get plugin options with defaults (using correct prefix)
 */
function lbn_get_option($key = null, $default = null) {
    $options = get_option('lbn_settings', array());
    
    $defaults = array(
        'custom_logo' => '',
        'background_image' => '',
        'notice_enabled' => 0,
        'notice_content' => '',
        'notice_start_date' => '',
        'notice_end_date' => ''
    );
    
    $options = wp_parse_args($options, $defaults);
    
    if ($key === null) {
        return $options;
    }
    
    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Update plugin option
 */
function lbn_update_option($key, $value) {
    $options = get_option('lbn_settings', array());
    $options[$key] = $value;
    return update_option('lbn_settings', $options);
}

/**
 * Check if current user can manage branding
 */
function lbn_current_user_can_manage() {
    return current_user_can('manage_options') || current_user_can('customize');
}

/**
 * Validate hex color
 */
function lbn_validate_hex_color($color) {
    if (empty($color)) {
        return false;
    }
    
    return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color);
}

/**
 * Get image dimensions
 */
function lbn_get_image_dimensions($image_url) {
    if (empty($image_url)) {
        return false;
    }
    
    $upload_dir = wp_upload_dir();
    $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
    
    if (file_exists($image_path)) {
        $dimensions = getimagesize($image_path);
        if ($dimensions) {
            return array(
                'width' => $dimensions[0],
                'height' => $dimensions[1]
            );
        }
    }
    
    return false;
}

/**
 * Generate CSS for login page background
 */
function lbn_get_background_css() {
    $background_image = lbn_get_option('background_image');
    
    if (empty($background_image)) {
        return '';
    }
    
    return "
        background-image: url('{$background_image}');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    ";
}

/**
 * Generate CSS for custom logo
 */
function lbn_get_logo_css() {
    $logo_url = lbn_get_option('custom_logo');
    
    if (empty($logo_url)) {
        return '';
    }
    
    $dimensions = lbn_get_image_dimensions($logo_url);
    $width = 320; // Default width
    $height = 80; // Default height
    
    if ($dimensions) {
        // Calculate proportional dimensions
        $ratio = $dimensions['width'] / $dimensions['height'];
        if ($ratio > 4) { // Very wide logo
            $height = 60;
            $width = min($dimensions['width'], 400);
        } elseif ($ratio < 1) { // Tall logo
            $width = 200;
            $height = min($dimensions['height'], 100);
        }
    }
    
    return "
        background-image: url('{$logo_url}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        width: {$width}px;
        height: {$height}px;
        max-width: 100%;
    ";
}

/**
 * Sanitize notice content
 */
function lbn_sanitize_notice_content($content) {
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'class' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'b' => array(),
        'i' => array(),
        'u' => array(),
        'p' => array(
            'class' => array()
        ),
        'h1' => array(
            'class' => array()
        ),
        'h2' => array(
            'class' => array()
        ),
        'h3' => array(
            'class' => array()
        ),
        'h4' => array(
            'class' => array()
        ),
        'ul' => array(
            'class' => array()
        ),
        'ol' => array(
            'class' => array()
        ),
        'li' => array(
            'class' => array()
        ),
        'div' => array(
            'class' => array()
        ),
        'span' => array(
            'class' => array(),
            'style' => array()
        ),
        'img' => array(
            'src' => array(),
            'alt' => array(),
            'class' => array(),
            'width' => array(),
            'height' => array()
        )
    );
    
    return wp_kses($content, $allowed_html);
}

/**
 * Display admin notices for requirements
 */
function lbn_display_requirements_notice() {
    $requirements_check = lbn_check_requirements();
    
    if ($requirements_check !== true) {
        foreach ($requirements_check as $error) {
            echo '<div class="notice notice-error"><p>' . esc_html($error) . '</p></div>';
        }
    }
}

/**
 * Get notice preview for admin
 */
function lbn_get_notice_preview() {
    $settings = lbn_get_settings();
    
    if (empty($settings['notice_content'])) {
        return __('No notice content set.', 'login-branding-notice');
    }
    
    $content = wp_trim_words(strip_tags($settings['notice_content']), 20, '...');
    $status = lbn_get_notice_status();
    
    return sprintf(
        __('Content: %s | Status: %s', 'login-branding-notice'),
        $content,
        $status
    );
}

/**
 * Get plugin information
 *
 * @return array Plugin data
 */
function lbn_get_plugin_info() {
    if (!function_exists('get_plugin_data')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return get_plugin_data(dirname(dirname(__FILE__)) . '/wp-login-notice.php');
}

/**
 * Debug logging function
 *
 * @param string $message Log message
 * @param mixed $data Additional data to log
 */
function lbn_debug_log($message, $data = null) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $log_message = '[Login Branding Notice] ' . $message;
        if ($data !== null) {
            $log_message .= ' | Data: ' . print_r($data, true);
        }
        error_log($log_message);
    }
}
