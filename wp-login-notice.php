<?php
/**
 * Plugin Name: Login Screen Branding & Notice
 * Plugin URI: https://github.com/mathiaschmid19/wp-login-branding
 * Description: Customize WordPress login screen with custom logo, background, and scheduled notices with WYSIWYG editor.
 * Version: 1.0.1
 * Author: Amine Ouhannou
 * Author URI: https://amineouhannou.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-login-branding
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('LBN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LBN_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LBN_VERSION', '1.0.1');

/**
 * Main Plugin Class
 */
class LoginBrandingNotice {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('wp-login-branding', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }
    
    public function init() {
        // Include required files first
        $this->includes();
        
        // Check requirements
        if (!lbn_check_requirements()) {
            return;
        }
        
        // Initialize admin
        if (is_admin()) {
            new LBN_Admin();
        }
        
        // Initialize frontend
        new LBN_Frontend();
    }
    
    private function includes() {
        require_once LBN_PLUGIN_PATH . 'includes/functions.php';
        require_once LBN_PLUGIN_PATH . 'includes/helpers.php';
        require_once LBN_PLUGIN_PATH . 'includes/class-admin.php';
        require_once LBN_PLUGIN_PATH . 'includes/class-frontend.php';
    }
    
    /**
     * Plugin activation hook
     */
    public static function activate() {
        // Set default options
        $default_options = array(
            'custom_logo' => '',
            'background_image' => '',
            'notice_content' => '',
            'notice_start_date' => '',
            'notice_end_date' => '',
            'notice_enabled' => 0
        );
        
        add_option('lbn_settings', $default_options);
    }
    
    /**
     * Plugin deactivation hook
     */
    public static function deactivate() {
        // Clean up scheduled events if any
        wp_clear_scheduled_hook('lbn_check_notices');
    }
    
    /**
     * Plugin uninstall hook
     */
    public static function uninstall() {
        // Remove options
        delete_option('lbn_settings');
        
        // Clean up any scheduled events
        wp_clear_scheduled_hook('lbn_check_notices');
    }
}

// Register activation, deactivation, and uninstall hooks
register_activation_hook(__FILE__, array('LoginBrandingNotice', 'activate'));
register_deactivation_hook(__FILE__, array('LoginBrandingNotice', 'deactivate'));
register_uninstall_hook(__FILE__, array('LoginBrandingNotice', 'uninstall'));

// Initialize the plugin
new LoginBrandingNotice();