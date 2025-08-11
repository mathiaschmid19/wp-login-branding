<?php
/**
 * WordPress Function Stubs for IDE Support
 * This file provides function signatures for common WordPress functions
 * to help IDEs like Intelephense understand WordPress code.
 * 
 * This file should NOT be included in production - it's for development only.
 */

// Prevent execution
if (!defined('ABSPATH')) {
    exit;
}

// Core WordPress functions
if (!function_exists('add_action')) {
    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {}
}

if (!function_exists('add_filter')) {
    function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {}
}

if (!function_exists('remove_action')) {
    function remove_action($hook_name, $callback, $priority = 10) {}
}

if (!function_exists('remove_filter')) {
    function remove_filter($hook_name, $callback, $priority = 10) {}
}

if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {}
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {}
}

if (!function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $object_name, $l10n) {}
}

if (!function_exists('wp_create_nonce')) {
    function wp_create_nonce($action = -1) {}
}

if (!function_exists('wp_verify_nonce')) {
    function wp_verify_nonce($nonce, $action = -1) {}
}

if (!function_exists('current_user_can')) {
    function current_user_can($capability, ...$args) {}
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {}
}

if (!function_exists('update_option')) {
    function update_option($option, $value, $autoload = null) {}
}

if (!function_exists('add_option')) {
    function add_option($option, $value = '', $deprecated = '', $autoload = 'yes') {}
}

if (!function_exists('delete_option')) {
    function delete_option($option) {}
}

if (!function_exists('register_setting')) {
    function register_setting($option_group, $option_name, $args = array()) {}
}

if (!function_exists('add_settings_section')) {
    function add_settings_section($id, $title, $callback, $page) {}
}

if (!function_exists('add_settings_field')) {
    function add_settings_field($id, $title, $callback, $page, $section = 'default', $args = array()) {}
}

if (!function_exists('settings_fields')) {
    function settings_fields($option_group) {}
}

if (!function_exists('do_settings_sections')) {
    function do_settings_sections($page) {}
}

if (!function_exists('add_options_page')) {
    function add_options_page($page_title, $menu_title, $capability, $menu_slug, $callback = '', $position = null) {}
}

if (!function_exists('add_menu_page')) {
    function add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback = '', $icon_url = '', $position = null) {}
}

if (!function_exists('esc_html')) {
    function esc_html($text) {}
}

if (!function_exists('esc_attr')) {
    function esc_attr($text) {}
}

if (!function_exists('esc_url')) {
    function esc_url($url, $protocols = null, $_context = 'display') {}
}

if (!function_exists('esc_url_raw')) {
    function esc_url_raw($url, $protocols = null) {}
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {}
}

if (!function_exists('wp_kses')) {
    function wp_kses($string, $allowed_html, $allowed_protocols = array()) {}
}

if (!function_exists('wp_kses_post')) {
    function wp_kses_post($data) {}
}

if (!function_exists('__')) {
    function __($text, $domain = 'default') {}
}

if (!function_exists('_e')) {
    function _e($text, $domain = 'default') {}
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = 'default') {}
}

if (!function_exists('_n')) {
    function _n($single, $plural, $number, $domain = 'default') {}
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {}
}

if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url($file) {}
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {}
}

if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {}
}

if (!function_exists('wp_enqueue_media')) {
    function wp_enqueue_media($args = array()) {}
}

if (!function_exists('wp_editor')) {
    function wp_editor($content, $editor_id, $settings = array()) {}
}

if (!function_exists('current_time')) {
    function current_time($type, $gmt = 0) {}
}

if (!function_exists('date_i18n')) {
    function date_i18n($format, $timestamp_with_offset = false, $gmt = false) {}
}

if (!function_exists('wp_upload_dir')) {
    function wp_upload_dir($time = null, $create_dir = true, $refresh_cache = false) {}
}

if (!function_exists('wp_parse_args')) {
    function wp_parse_args($args, $defaults = array()) {}
}

if (!function_exists('wp_trim_words')) {
    function wp_trim_words($text, $num_words = 55, $more = null) {}
}

if (!function_exists('wp_clear_scheduled_hook')) {
    function wp_clear_scheduled_hook($hook, $args = array(), $wp_error = false) {}
}

if (!function_exists('register_activation_hook')) {
    function register_activation_hook($file, $callback) {}
}

if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook($file, $callback) {}
}

if (!function_exists('register_uninstall_hook')) {
    function register_uninstall_hook($file, $callback) {}
}

if (!function_exists('is_admin')) {
    function is_admin() {}
}

if (!function_exists('wp_die')) {
    function wp_die($message = '', $title = '', $args = array()) {}
}

if (!function_exists('wp_redirect')) {
    function wp_redirect($location, $status = 302, $x_redirect_by = 'WordPress') {}
}

if (!function_exists('wp_safe_redirect')) {
    function wp_safe_redirect($location, $status = 302, $x_redirect_by = 'WordPress') {}
}

if (!function_exists('apply_filters')) {
    function apply_filters($hook_name, $value, ...$args) {}
}

if (!function_exists('do_action')) {
    function do_action($hook_name, ...$args) {}
}

if (!function_exists('get_plugin_data')) {
    function get_plugin_data($plugin_file, $markup = true, $translate = true) {}
}

if (!function_exists('error_log')) {
    function error_log($message, $message_type = 0, $destination = null, $extra_headers = null) {}
}

// Global variables
if (!isset($wp_version)) {
    $wp_version = '6.0';
}

if (!defined('ABSPATH')) {
    define('ABSPATH', '/');
}

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', false);
}

if (!defined('WP_DEBUG_LOG')) {
    define('WP_DEBUG_LOG', false);
}

if (!defined('WP_DEBUG_DISPLAY')) {
    define('WP_DEBUG_DISPLAY', false);
}