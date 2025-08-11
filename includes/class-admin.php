<?php
/**
 * Admin functionality for Login Branding Notice plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LBN_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'), 10);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_notices', array($this, 'admin_notices'));
        add_action('wp_ajax_lbn_preview_notice', array($this, 'ajax_preview_notice'));
        
        // Force settings registration on plugin pages
        if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'login-branding-notice') {
            add_action('current_screen', array($this, 'ensure_settings_registered'));
            // Remove default WordPress admin footer on our plugin page
            add_filter('admin_footer_text', array($this, 'remove_admin_footer_text'));
            add_filter('update_footer', array($this, 'remove_update_footer'), 11);
        }
    }
    
    /**
     * Ensure settings are registered when viewing our admin page
     */
    public function ensure_settings_registered() {
        $this->register_settings();
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Login Branding & Notice', 'login-branding-notice'), // Page title
            __('Login Branding', 'login-branding-notice'),          // Menu title
            'manage_options',                                        // Capability
            'login-branding-notice',                                 // Menu slug
            array($this, 'admin_page'),                             // Callback function
            'dashicons-admin-appearance',                           // Icon
            30                                                      // Position
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('lbn_settings_group', 'lbn_settings', array($this, 'sanitize_settings'));
        
        // Add settings sections
        add_settings_section(
            'lbn_branding_section',
            __('Login Branding Settings', 'login-branding-notice'),
            array($this, 'branding_section_callback'),
            'login-branding-notice'
        );
        
        add_settings_section(
            'lbn_notice_section',
            __('Notice Settings', 'login-branding-notice'),
            array($this, 'notice_section_callback'),
            'login-branding-notice'
        );
        
        // Add settings fields
        add_settings_field(
            'custom_logo',
            __('Custom Logo', 'login-branding-notice'),
            array($this, 'custom_logo_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'background_image',
            __('Background Image', 'login-branding-notice'),
            array($this, 'background_image_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'background_color',
            __('Background Color', 'login-branding-notice'),
            array($this, 'background_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'background_overlay',
            __('Background Overlay', 'login-branding-notice'),
            array($this, 'background_overlay_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'lost_password_color',
            __('Lost Password Link Color', 'login-branding-notice'),
            array($this, 'lost_password_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'back_to_site_color',
            __('Back to Site Link Color', 'login-branding-notice'),
            array($this, 'back_to_site_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_background_color',
            __('Login Form Background Color', 'login-branding-notice'),
            array($this, 'form_background_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_background_opacity',
            __('Login Form Background Opacity', 'login-branding-notice'),
            array($this, 'form_background_opacity_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_border_radius',
            __('Login Form Border Radius', 'login-branding-notice'),
            array($this, 'form_border_radius_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_shadow',
            __('Login Form Shadow', 'login-branding-notice'),
            array($this, 'form_shadow_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_text_color',
            __('Login Form Text Color', 'login-branding-notice'),
            array($this, 'form_text_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_font_family',
            __('Login Form Font Family', 'login-branding-notice'),
            array($this, 'form_font_family_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_font_size',
            __('Login Form Font Size', 'login-branding-notice'),
            array($this, 'form_font_size_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'form_font_weight',
            __('Login Form Font Weight', 'login-branding-notice'),
            array($this, 'form_font_weight_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_background_color',
            __('Login Button Background Color', 'login-branding-notice'),
            array($this, 'button_background_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_text_color',
            __('Login Button Text Color', 'login-branding-notice'),
            array($this, 'button_text_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_hover_background_color',
            __('Login Button Hover Background Color', 'login-branding-notice'),
            array($this, 'button_hover_background_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_hover_text_color',
            __('Login Button Hover Text Color', 'login-branding-notice'),
            array($this, 'button_hover_text_color_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_border_radius',
            __('Login Button Border Radius', 'login-branding-notice'),
            array($this, 'button_border_radius_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_font_size',
            __('Login Button Font Size', 'login-branding-notice'),
            array($this, 'button_font_size_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'button_font_weight',
            __('Login Button Font Weight', 'login-branding-notice'),
            array($this, 'button_font_weight_callback'),
            'login-branding-notice',
            'lbn_branding_section'
        );
        
        add_settings_field(
            'notice_enabled',
            __('Enable Notice', 'login-branding-notice'),
            array($this, 'notice_enabled_callback'),
            'login-branding-notice',
            'lbn_notice_section'
        );
        
        add_settings_field(
            'notice_content',
            __('Notice Content', 'login-branding-notice'),
            array($this, 'notice_content_callback'),
            'login-branding-notice',
            'lbn_notice_section'
        );
        
        add_settings_field(
            'notice_dates',
            __('Notice Schedule', 'login-branding-notice'),
            array($this, 'notice_dates_callback'),
            'login-branding-notice',
            'lbn_notice_section'
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_login-branding-notice') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', 'https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css');
        
        // Enqueue admin styles
        wp_enqueue_style('lbn-admin-styles', LBN_PLUGIN_URL . 'assets/admin-styles.css', array(), lbn_get_plugin_version());
        
        // Enqueue admin scripts
        wp_enqueue_script('lbn-admin-scripts', LBN_PLUGIN_URL . 'assets/admin-scripts.js', array('jquery', 'media-upload', 'jquery-ui-datepicker'), lbn_get_plugin_version(), true);
        
        // Localize script
        wp_localize_script('lbn-admin-scripts', 'lbnAdmin', array(
            'previewNonce' => wp_create_nonce('lbn_preview_nonce'),
            'strings' => array(
                'enterContent' => __('Please enter some notice content first.', 'login-branding-notice'),
                'loadingPreview' => __('Loading preview...', 'login-branding-notice'),
                'errorLoading' => __('Error loading preview.', 'login-branding-notice'),
                'invalidLogoUrl' => __('Please enter a valid logo URL.', 'login-branding-notice'),
                'invalidBgUrl' => __('Please enter a valid background image URL.', 'login-branding-notice'),
                'invalidStartDate' => __('Please enter a valid start date (YYYY-MM-DD).', 'login-branding-notice'),
                'invalidEndDate' => __('Please enter a valid end date (YYYY-MM-DD).', 'login-branding-notice'),
                'startAfterEnd' => __('Start date cannot be after end date.', 'login-branding-notice'),
                'validationErrors' => __('Please fix the following errors:', 'login-branding-notice'),
                'confirmRemove' => __('Are you sure you want to remove this image?', 'login-branding-notice')
            )
        ));
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        $sanitized['custom_logo'] = esc_url_raw($input['custom_logo']);
        $sanitized['background_image'] = esc_url_raw($input['background_image']);
        $sanitized['background_color'] = sanitize_hex_color($input['background_color']);
        $sanitized['background_overlay_color'] = sanitize_hex_color($input['background_overlay_color']);
        $sanitized['background_overlay_opacity'] = floatval($input['background_overlay_opacity']);
        $sanitized['lost_password_color'] = sanitize_hex_color($input['lost_password_color']);
        $sanitized['back_to_site_color'] = sanitize_hex_color($input['back_to_site_color']);
        $sanitized['form_background_color'] = sanitize_hex_color($input['form_background_color']);
        $sanitized['form_background_opacity'] = floatval($input['form_background_opacity']);
        $sanitized['form_border_radius'] = intval($input['form_border_radius']);
        $sanitized['form_shadow'] = isset($input['form_shadow']) ? 1 : 0;
        $sanitized['form_text_color'] = sanitize_hex_color($input['form_text_color']);
        $sanitized['form_font_family'] = sanitize_text_field($input['form_font_family']);
        $sanitized['form_font_size'] = intval($input['form_font_size']);
        $sanitized['form_font_weight'] = sanitize_text_field($input['form_font_weight']);
        $sanitized['button_background_color'] = sanitize_hex_color($input['button_background_color']);
        $sanitized['button_text_color'] = sanitize_hex_color($input['button_text_color']);
        $sanitized['button_hover_background_color'] = sanitize_hex_color($input['button_hover_background_color']);
        $sanitized['button_hover_text_color'] = sanitize_hex_color($input['button_hover_text_color']);
        $sanitized['button_border_radius'] = intval($input['button_border_radius']);
        $sanitized['button_font_size'] = intval($input['button_font_size']);
        $sanitized['button_font_weight'] = sanitize_text_field($input['button_font_weight']);
        $sanitized['notice_content'] = wp_kses_post($input['notice_content']);
        $sanitized['notice_start_date'] = sanitize_text_field($input['notice_start_date']);
        $sanitized['notice_end_date'] = sanitize_text_field($input['notice_end_date']);
        $sanitized['notice_enabled'] = isset($input['notice_enabled']) ? 1 : 0;
        
        // Ensure overlay opacity is between 0 and 1
        if ($sanitized['background_overlay_opacity'] < 0) {
            $sanitized['background_overlay_opacity'] = 0;
        } elseif ($sanitized['background_overlay_opacity'] > 1) {
            $sanitized['background_overlay_opacity'] = 1;
        }
        
        // Ensure form background opacity is between 0 and 1
        if ($sanitized['form_background_opacity'] < 0) {
            $sanitized['form_background_opacity'] = 0;
        } elseif ($sanitized['form_background_opacity'] > 1) {
            $sanitized['form_background_opacity'] = 1;
        }
        
        // Ensure border radius is not negative
        if ($sanitized['form_border_radius'] < 0) {
            $sanitized['form_border_radius'] = 0;
        }
        
        // Ensure font size is positive
        if ($sanitized['form_font_size'] < 8) {
            $sanitized['form_font_size'] = 14; // Default font size
        } elseif ($sanitized['form_font_size'] > 32) {
            $sanitized['form_font_size'] = 32; // Maximum font size
        }
        
        // Ensure button border radius is not negative
        if ($sanitized['button_border_radius'] < 0) {
            $sanitized['button_border_radius'] = 0;
        }
        
        // Ensure button font size is positive
        if ($sanitized['button_font_size'] < 8) {
            $sanitized['button_font_size'] = 14; // Default font size
        } elseif ($sanitized['button_font_size'] > 32) {
            $sanitized['button_font_size'] = 32; // Maximum font size
        }
        
        return $sanitized;
    }
    
    /**
     * Section callbacks
     */
    public function branding_section_callback() {
        echo '<p class="lbn-section-description">' . __('Customize the appearance of your login screen.', 'login-branding-notice') . '</p>';
    }
    
    public function notice_section_callback() {
        echo '<p class="lbn-section-description">' . __('Configure notices to display on the login screen.', 'login-branding-notice') . '</p>';
    }
    
    /**
     * Field callbacks
     */
    public function custom_logo_callback() {
        $options = get_option('lbn_settings', array());
        $logo_url = isset($options['custom_logo']) ? $options['custom_logo'] : '';
        
        echo '<div class="lbn-image-upload-field">';
        echo '<input type="url" id="custom_logo" name="lbn_settings[custom_logo]" value="' . esc_attr($logo_url) . '" class="regular-text" />';
        echo '<input type="button" id="upload_logo_button" class="button" value="' . __('Upload Logo', 'login-branding-notice') . '" />';
        
        if ($logo_url) {
            echo '<input type="button" class="button remove-image" data-target="custom_logo" value="' . __('Remove', 'login-branding-notice') . '" />';
        }
        
        if ($logo_url) {
            echo '<br><img id="custom_logo_preview" src="' . esc_url($logo_url) . '" class="lbn-image-preview" />';
        } else {
            echo '<br><img id="custom_logo_preview" src="" class="lbn-image-preview" style="display:none;" />';
        }
        
        echo '<p class="description">' . __('Upload a custom logo for the login screen. Recommended size: 320x80 pixels.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function background_image_callback() {
        $options = get_option('lbn_settings', array());
        $bg_url = isset($options['background_image']) ? $options['background_image'] : '';
        
        echo '<div class="lbn-image-upload-field">';
        echo '<input type="url" id="background_image" name="lbn_settings[background_image]" value="' . esc_attr($bg_url) . '" class="regular-text" />';
        echo '<input type="button" id="upload_bg_button" class="button" value="' . __('Upload Background', 'login-branding-notice') . '" />';
        
        if ($bg_url) {
            echo '<input type="button" class="button remove-image" data-target="background_image" value="' . __('Remove', 'login-branding-notice') . '" />';
        }
        
        if ($bg_url) {
            echo '<br><img id="background_image_preview" src="' . esc_url($bg_url) . '" class="lbn-image-preview" />';
        } else {
            echo '<br><img id="background_image_preview" src="" class="lbn-image-preview" style="display:none;" />';
        }
        
        echo '<p class="description">' . __('Upload a background image for the login screen. The image will be displayed as cover (full screen) and centered. Recommended size: 1920x1080 pixels or higher.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function background_color_callback() {
        $options = get_option('lbn_settings', array());
        $bg_color = isset($options['background_color']) ? $options['background_color'] : '#f1f1f1';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="color" id="background_color" name="lbn_settings[background_color]" value="' . esc_attr($bg_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="background_color_text" value="' . esc_attr($bg_color) . '" class="lbn-color-text" readonly />';
        echo '<p class="description">' . __('Choose a background color for the login page. This will be used as a fallback or combined with the background image.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function background_overlay_callback() {
        $options = get_option('lbn_settings', array());
        $overlay_color = isset($options['background_overlay_color']) ? $options['background_overlay_color'] : '#000000';
        $overlay_opacity = isset($options['background_overlay_opacity']) ? $options['background_overlay_opacity'] : 0.3;
        
        echo '<div class="lbn-overlay-field">';
        echo '<div class="lbn-overlay-controls">';
        echo '<div class="lbn-overlay-color">';
        echo '<label for="background_overlay_color">' . __('Overlay Color:', 'login-branding-notice') . '</label>';
        echo '<input type="color" id="background_overlay_color" name="lbn_settings[background_overlay_color]" value="' . esc_attr($overlay_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="background_overlay_color_text" value="' . esc_attr($overlay_color) . '" class="lbn-color-text" readonly />';
        echo '</div>';
        echo '<div class="lbn-overlay-opacity">';
        echo '<label for="background_overlay_opacity">' . __('Overlay Opacity:', 'login-branding-notice') . '</label>';
        echo '<input type="range" id="background_overlay_opacity" name="lbn_settings[background_overlay_opacity]" min="0" max="1" step="0.1" value="' . esc_attr($overlay_opacity) . '" class="lbn-opacity-slider" />';
        echo '<span class="lbn-opacity-value">' . esc_html($overlay_opacity) . '</span>';
        echo '</div>';
        echo '</div>';
        echo '<p class="description">' . __('Add a colored overlay on top of the background image to improve text readability. Adjust the opacity to control the overlay intensity.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function lost_password_color_callback() {
        $options = get_option('lbn_settings', array());
        $lost_password_color = isset($options['lost_password_color']) ? $options['lost_password_color'] : '#2271b1';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="color" id="lost_password_color" name="lbn_settings[lost_password_color]" value="' . esc_attr($lost_password_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="lost_password_color_text" value="' . esc_attr($lost_password_color) . '" class="lbn-color-text" readonly />';
        echo '<p class="description">' . __('Choose the color for the "Lost your password?" link text.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function back_to_site_color_callback() {
        $options = get_option('lbn_settings', array());
        $back_to_site_color = isset($options['back_to_site_color']) ? $options['back_to_site_color'] : '#2271b1';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="color" id="back_to_site_color" name="lbn_settings[back_to_site_color]" value="' . esc_attr($back_to_site_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="back_to_site_color_text" value="' . esc_attr($back_to_site_color) . '" class="lbn-color-text" readonly />';
        echo '<p class="description">' . __('Choose the color for the "← Go to [site name]" link text.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_background_color_callback() {
        $options = get_option('lbn_settings', array());
        $form_bg_color = isset($options['form_background_color']) ? $options['form_background_color'] : '#ffffff';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="color" id="form_background_color" name="lbn_settings[form_background_color]" value="' . esc_attr($form_bg_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="form_background_color_text" value="' . esc_attr($form_bg_color) . '" class="lbn-color-text" readonly />';
        echo '<p class="description">' . __('Choose the background color for the login form box.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_background_opacity_callback() {
        $options = get_option('lbn_settings', array());
        $form_opacity = isset($options['form_background_opacity']) ? $options['form_background_opacity'] : 1.0;
        
        echo '<div class="lbn-opacity-field">';
        echo '<input type="range" id="form_background_opacity" name="lbn_settings[form_background_opacity]" min="0" max="1" step="0.1" value="' . esc_attr($form_opacity) . '" class="lbn-opacity-slider" />';
        echo '<span class="lbn-opacity-value">' . esc_html($form_opacity) . '</span>';
        echo '<p class="description">' . __('Adjust the opacity of the login form background. 0 = transparent, 1 = fully opaque.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_border_radius_callback() {
        $options = get_option('lbn_settings', array());
        $border_radius = isset($options['form_border_radius']) ? $options['form_border_radius'] : 0;
        
        echo '<div class="lbn-number-field">';
        echo '<input type="number" id="form_border_radius" name="lbn_settings[form_border_radius]" value="' . esc_attr($border_radius) . '" min="0" max="50" class="lbn-number-input" />';
        echo '<span class="lbn-unit">px</span>';
        echo '<p class="description">' . __('Set the border radius for the login form corners. 0 = square corners, higher values = more rounded.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_shadow_callback() {
        $options = get_option('lbn_settings', array());
        $form_shadow = isset($options['form_shadow']) ? $options['form_shadow'] : 1;
        
        echo '<div class="lbn-checkbox-field">';
        echo '<input type="checkbox" id="form_shadow" name="lbn_settings[form_shadow]" value="1" ' . checked(1, $form_shadow, false) . ' />';
        echo '<label for="form_shadow">' . __('Enable drop shadow for the login form', 'login-branding-notice') . '</label>';
        echo '<p class="description">' . __('Add a subtle drop shadow around the login form for better visual separation.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_text_color_callback() {
        $options = get_option('lbn_settings', array());
        $text_color = isset($options['form_text_color']) ? $options['form_text_color'] : '#333333';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="color" id="form_text_color" name="lbn_settings[form_text_color]" value="' . esc_attr($text_color) . '" class="lbn-color-picker" />';
        echo '<input type="text" id="form_text_color_text" value="' . esc_attr($text_color) . '" class="lbn-color-text" readonly />';
        echo '<p class="description">' . __('Choose the text color for labels and text inside the login form.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function form_font_family_callback() {
        $options = get_option('lbn_settings', array());
        $font_family = isset($options['form_font_family']) ? $options['form_font_family'] : 'default';
        
        $fonts = array(
            'default' => __('Default (WordPress)', 'login-branding-notice'),
            'Arial, sans-serif' => 'Arial',
            'Helvetica, Arial, sans-serif' => 'Helvetica',
            '"Times New Roman", Times, serif' => 'Times New Roman',
            'Georgia, serif' => 'Georgia',
            '"Courier New", Courier, monospace' => 'Courier New',
            'Verdana, Geneva, sans-serif' => 'Verdana',
            'Tahoma, Geneva, sans-serif' => 'Tahoma',
            '"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
            '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans',
            '"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
            '"Open Sans", sans-serif' => 'Open Sans',
            '"Roboto", sans-serif' => 'Roboto',
            '"Lato", sans-serif' => 'Lato',
            '"Montserrat", sans-serif' => 'Montserrat',
            '"Source Sans Pro", sans-serif' => 'Source Sans Pro'
        );
        
        echo '<select id="form_font_family" name="lbn_settings[form_font_family]" class="lbn-select">';
        foreach ($fonts as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($font_family, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Select the font family for the login form text. Google Fonts require additional setup.', 'login-branding-notice') . '</p>';
    }
    
    public function form_font_size_callback() {
        $options = get_option('lbn_settings', array());
        $font_size = isset($options['form_font_size']) ? $options['form_font_size'] : 14;
        
        echo '<div style="display: flex; gap: 10px; align-items: center;">';
        echo '<input type="number" id="form_font_size" name="lbn_settings[form_font_size]" value="' . esc_attr($font_size) . '" min="8" max="32" class="lbn-input" style="width: 80px;" />';
        echo '<span style="color: #666;">px</span>';
        echo '</div>';
        echo '<p class="description">' . __('Set the font size for the login form text. Range: 8-32 pixels.', 'login-branding-notice') . '</p>';
    }
    
    public function form_font_weight_callback() {
        $options = get_option('lbn_settings', array());
        $font_weight = isset($options['form_font_weight']) ? $options['form_font_weight'] : 'normal';
        
        $weights = array(
            'normal' => __('Normal (400)', 'login-branding-notice'),
            'bold' => __('Bold (700)', 'login-branding-notice'),
            '300' => __('Light (300)', 'login-branding-notice'),
            '500' => __('Medium (500)', 'login-branding-notice'),
            '600' => __('Semi Bold (600)', 'login-branding-notice'),
            '800' => __('Extra Bold (800)', 'login-branding-notice')
        );
        
        echo '<select id="form_font_weight" name="lbn_settings[form_font_weight]" class="lbn-select">';
        foreach ($weights as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($font_weight, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Choose the font weight (boldness) for the login form text.', 'login-branding-notice') . '</p>';
    }
    
    public function button_background_color_callback() {
        $options = get_option('lbn_settings', array());
        $color = isset($options['button_background_color']) ? $options['button_background_color'] : '#0073aa';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="text" id="button_background_color" name="lbn_settings[button_background_color]" value="' . esc_attr($color) . '" class="lbn-color-picker" data-default-color="#0073aa" />';
        echo '<input type="color" class="lbn-color-input" value="' . esc_attr($color) . '" />';
        echo '</div>';
        echo '<p class="description">' . __('Choose the background color for the login button.', 'login-branding-notice') . '</p>';
    }
    
    public function button_text_color_callback() {
        $options = get_option('lbn_settings', array());
        $color = isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="text" id="button_text_color" name="lbn_settings[button_text_color]" value="' . esc_attr($color) . '" class="lbn-color-picker" data-default-color="#ffffff" />';
        echo '<input type="color" class="lbn-color-input" value="' . esc_attr($color) . '" />';
        echo '</div>';
        echo '<p class="description">' . __('Choose the text color for the login button.', 'login-branding-notice') . '</p>';
    }
    
    public function button_hover_background_color_callback() {
        $options = get_option('lbn_settings', array());
        $color = isset($options['button_hover_background_color']) ? $options['button_hover_background_color'] : '#005a87';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="text" id="button_hover_background_color" name="lbn_settings[button_hover_background_color]" value="' . esc_attr($color) . '" class="lbn-color-picker" data-default-color="#005a87" />';
        echo '<input type="color" class="lbn-color-input" value="' . esc_attr($color) . '" />';
        echo '</div>';
        echo '<p class="description">' . __('Choose the background color for the login button when hovered.', 'login-branding-notice') . '</p>';
    }
    
    public function button_hover_text_color_callback() {
        $options = get_option('lbn_settings', array());
        $color = isset($options['button_hover_text_color']) ? $options['button_hover_text_color'] : '#ffffff';
        
        echo '<div class="lbn-color-field">';
        echo '<input type="text" id="button_hover_text_color" name="lbn_settings[button_hover_text_color]" value="' . esc_attr($color) . '" class="lbn-color-picker" data-default-color="#ffffff" />';
        echo '<input type="color" class="lbn-color-input" value="' . esc_attr($color) . '" />';
        echo '</div>';
        echo '<p class="description">' . __('Choose the text color for the login button when hovered.', 'login-branding-notice') . '</p>';
    }
    
    public function button_border_radius_callback() {
        $options = get_option('lbn_settings', array());
        $radius = isset($options['button_border_radius']) ? $options['button_border_radius'] : 3;
        
        echo '<input type="number" id="button_border_radius" name="lbn_settings[button_border_radius]" value="' . esc_attr($radius) . '" min="0" max="50" class="lbn-input" />';
        echo '<span class="lbn-unit">px</span>';
        echo '<p class="description">' . __('Set the border radius for the login button. Range: 0-50 pixels.', 'login-branding-notice') . '</p>';
    }
    
    public function button_font_size_callback() {
        $options = get_option('lbn_settings', array());
        $font_size = isset($options['button_font_size']) ? $options['button_font_size'] : 14;
        
        echo '<input type="number" id="button_font_size" name="lbn_settings[button_font_size]" value="' . esc_attr($font_size) . '" min="8" max="32" class="lbn-input" />';
        echo '<span class="lbn-unit">px</span>';
        echo '<p class="description">' . __('Set the font size for the login button text. Range: 8-32 pixels.', 'login-branding-notice') . '</p>';
    }
    
    public function button_font_weight_callback() {
        $options = get_option('lbn_settings', array());
        $font_weight = isset($options['button_font_weight']) ? $options['button_font_weight'] : 'normal';
        
        $weights = array(
            'normal' => __('Normal (400)', 'login-branding-notice'),
            'bold' => __('Bold (700)', 'login-branding-notice'),
            '300' => __('Light (300)', 'login-branding-notice'),
            '500' => __('Medium (500)', 'login-branding-notice'),
            '600' => __('Semi Bold (600)', 'login-branding-notice'),
            '800' => __('Extra Bold (800)', 'login-branding-notice')
        );
        
        echo '<select id="button_font_weight" name="lbn_settings[button_font_weight]" class="lbn-select">';
        foreach ($weights as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($font_weight, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . __('Choose the font weight (boldness) for the login button text.', 'login-branding-notice') . '</p>';
    }
    
    public function notice_enabled_callback() {
        $options = get_option('lbn_settings', array());
        $enabled = isset($options['notice_enabled']) ? $options['notice_enabled'] : 0;
        
        echo '<input type="checkbox" id="notice_enabled" name="lbn_settings[notice_enabled]" value="1" ' . checked(1, $enabled, false) . ' />';
        echo '<label for="notice_enabled">' . __('Enable notice display on login screen', 'login-branding-notice') . '</label>';
    }
    
    public function notice_content_callback() {
        $options = get_option('lbn_settings', array());
        $content = isset($options['notice_content']) ? $options['notice_content'] : '';
        
        echo '<div class="notice-settings-fields">';
        
        wp_editor($content, 'notice_content', array(
            'textarea_name' => 'lbn_settings[notice_content]',
            'textarea_rows' => 10,
            'media_buttons' => true,
            'teeny' => false,
            'tinymce' => true,
            'quicktags' => true
        ));
        
        echo '<p class="description">' . __('Enter the notice content to display on the login screen. You can use the rich editor to format text, add links, and insert media.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    public function notice_dates_callback() {
        $options = get_option('lbn_settings', array());
        $start_date = isset($options['notice_start_date']) ? $options['notice_start_date'] : '';
        $end_date = isset($options['notice_end_date']) ? $options['notice_end_date'] : '';
        
        echo '<div class="notice-settings-fields">';
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<td style="padding-left: 0;"><label for="notice_start_date">' . __('Start Date:', 'login-branding-notice') . '</label></td>';
        echo '<td><input type="text" id="notice_start_date" name="lbn_settings[notice_start_date]" value="' . esc_attr($start_date) . '" class="lbn-date-field" placeholder="YYYY-MM-DD" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td style="padding-left: 0;"><label for="notice_end_date">' . __('End Date:', 'login-branding-notice') . '</label></td>';
        echo '<td><input type="text" id="notice_end_date" name="lbn_settings[notice_end_date]" value="' . esc_attr($end_date) . '" class="lbn-date-field" placeholder="YYYY-MM-DD" /></td>';
        echo '</tr>';
        echo '</table>';
        echo '<p class="description">' . __('Leave dates empty to display the notice permanently when enabled. Use YYYY-MM-DD format.', 'login-branding-notice') . '</p>';
        echo '</div>';
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        // Debug: Check if settings sections are registered
        global $wp_settings_sections, $wp_settings_fields;
        $options = get_option('lbn_settings', array());
        
        ?>
        <div class="wrap">
            <h1>
                <span class="dashicons dashicons-admin-appearance" style="font-size: 28px; margin-right: 10px; color: #667eea;"></span>
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>
            <p style="font-size: 16px; color: #646970; margin-bottom: 30px;">
                <?php _e('Customize your WordPress login page with beautiful branding and notices.', 'login-branding-notice'); ?>
            </p>
            
            <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
            <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
                <strong>Debug Info:</strong><br>
                Sections registered: <?php echo isset($wp_settings_sections['login-branding-notice']) ? 'YES' : 'NO'; ?><br>
                Fields registered: <?php echo isset($wp_settings_fields['login-branding-notice']) ? 'YES' : 'NO'; ?><br>
                Current user can manage options: <?php echo current_user_can('manage_options') ? 'YES' : 'NO'; ?>
            </div>
            <?php endif; ?>
            
            <div class="lbn-admin-container">
                <div class="lbn-main-content">
                    <form method="post" action="options.php">
                        <?php settings_fields('lbn_settings_group'); ?>
                        
                        <!-- Tab Navigation -->
                        <div class="lbn-tab-navigation">
                            <button type="button" class="lbn-tab-button active" data-tab="branding">
                                <span class="dashicons dashicons-format-image"></span>
                                <?php _e('Branding & Styling', 'login-branding-notice'); ?>
                            </button>
                            <button type="button" class="lbn-tab-button" data-tab="form-styling">
                                <span class="dashicons dashicons-admin-customizer"></span>
                                <?php _e('Form Styling', 'login-branding-notice'); ?>
                            </button>
                            <button type="button" class="lbn-tab-button" data-tab="notices">
                                <span class="dashicons dashicons-megaphone"></span>
                                <?php _e('Notice Settings', 'login-branding-notice'); ?>
                            </button>
                        </div>
                        
                        <!-- Tab Content -->
                        <div class="lbn-tab-content">
                            
                            <!-- Branding & Styling Tab -->
                            <div class="lbn-tab-panel active" id="branding-panel">
                                <div class="lbn-settings-card">
                                    <div class="lbn-card-body">
                                <table class="lbn-form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="custom_logo"><?php _e('Custom Logo', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-image-upload">
                                                <input type="url" id="custom_logo" name="lbn_settings[custom_logo]" 
                                                       value="<?php echo esc_attr(isset($options['custom_logo']) ? $options['custom_logo'] : ''); ?>" 
                                                       class="lbn-input" placeholder="<?php _e('Enter logo URL or upload image', 'login-branding-notice'); ?>" />
                                                
                                                <div class="lbn-image-controls">
                                                    <button type="button" class="lbn-upload-btn upload-image" data-target="custom_logo">
                                                        <span class="dashicons dashicons-upload" style="margin-right: 5px;"></span>
                                                        <?php _e('Upload Logo', 'login-branding-notice'); ?>
                                                    </button>
                                                    <?php if (!empty($options['custom_logo'])): ?>
                                                    <button type="button" class="lbn-remove-btn remove-image" data-target="custom_logo">
                                                        <span class="dashicons dashicons-trash" style="margin-right: 5px;"></span>
                                                        <?php _e('Remove', 'login-branding-notice'); ?>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!empty($options['custom_logo'])): ?>
                                                <div class="lbn-image-preview has-image" id="custom_logo_preview">
                                                    <img src="<?php echo esc_url($options['custom_logo']); ?>" alt="<?php _e('Custom Logo Preview', 'login-branding-notice'); ?>" />
                                                </div>
                                                <?php else: ?>
                                                <div class="lbn-image-preview" id="custom_logo_preview" style="display: none;">
                                                    <img src="" alt="<?php _e('Custom Logo Preview', 'login-branding-notice'); ?>" />
                                                </div>
                                                <?php endif; ?>
                                                
                                                <p class="lbn-description">
                                                    <?php _e('Upload a custom logo to replace the default WordPress logo on the login page. Recommended size: 320x80 pixels.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="background_image"><?php _e('Background Image', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-image-upload">
                                                <input type="url" id="background_image" name="lbn_settings[background_image]" 
                                                       value="<?php echo esc_attr(isset($options['background_image']) ? $options['background_image'] : ''); ?>" 
                                                       class="lbn-input" placeholder="<?php _e('Enter background image URL or upload image', 'login-branding-notice'); ?>" />
                                                
                                                <div class="lbn-image-controls">
                                                    <button type="button" class="lbn-upload-btn upload-image" data-target="background_image">
                                                        <span class="dashicons dashicons-upload" style="margin-right: 5px;"></span>
                                                        <?php _e('Upload Background', 'login-branding-notice'); ?>
                                                    </button>
                                                    <?php if (!empty($options['background_image'])): ?>
                                                    <button type="button" class="lbn-remove-btn remove-image" data-target="background_image">
                                                        <span class="dashicons dashicons-trash" style="margin-right: 5px;"></span>
                                                        <?php _e('Remove', 'login-branding-notice'); ?>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!empty($options['background_image'])): ?>
                                                <div class="lbn-image-preview has-image" id="background_image_preview">
                                                    <img src="<?php echo esc_url($options['background_image']); ?>" alt="<?php _e('Background Image Preview', 'login-branding-notice'); ?>" />
                                                </div>
                                                <?php else: ?>
                                                <div class="lbn-image-preview" id="background_image_preview" style="display: none;">
                                                    <img src="" alt="<?php _e('Background Image Preview', 'login-branding-notice'); ?>" />
                                                </div>
                                                <?php endif; ?>
                                                
                                                <p class="lbn-description">
                                                    <?php _e('Set a custom background image for the login page. The image will be displayed as cover and centered for optimal appearance.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="background_color"><?php _e('Background Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="background_color" name="lbn_settings[background_color]" 
                                                       value="<?php echo esc_attr(isset($options['background_color']) ? $options['background_color'] : '#f1f1f1'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="background_color_text" 
                                                       value="<?php echo esc_attr(isset($options['background_color']) ? $options['background_color'] : '#f1f1f1'); ?>" 
                                                       class="lbn-color-text" readonly />
                                                <p class="lbn-description">
                                                    <?php _e('Choose a background color for the login page. This will be used as a fallback or combined with the background image.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label><?php _e('Background Overlay', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-overlay-field">
                                                <div class="lbn-overlay-controls">
                                                    <div class="lbn-overlay-color">
                                                        <label for="background_overlay_color" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                                            <?php _e('Overlay Color:', 'login-branding-notice'); ?>
                                                        </label>
                                                        <div style="display: flex; gap: 10px; align-items: center;">
                                                            <input type="color" id="background_overlay_color" name="lbn_settings[background_overlay_color]" 
                                                                   value="<?php echo esc_attr(isset($options['background_overlay_color']) ? $options['background_overlay_color'] : '#000000'); ?>" 
                                                                   class="lbn-color-picker" />
                                                            <input type="text" id="background_overlay_color_text" 
                                                                   value="<?php echo esc_attr(isset($options['background_overlay_color']) ? $options['background_overlay_color'] : '#000000'); ?>" 
                                                                   class="lbn-color-text" readonly />
                                                        </div>
                                                    </div>
                                                    <div class="lbn-overlay-opacity" style="margin-top: 15px;">
                                                        <label for="background_overlay_opacity" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                                            <?php _e('Overlay Opacity:', 'login-branding-notice'); ?>
                                                        </label>
                                                        <div style="display: flex; gap: 10px; align-items: center;">
                                                            <input type="range" id="background_overlay_opacity" name="lbn_settings[background_overlay_opacity]" 
                                                                   min="0" max="1" step="0.1" 
                                                                   value="<?php echo esc_attr(isset($options['background_overlay_opacity']) ? $options['background_overlay_opacity'] : 0.3); ?>" 
                                                                   class="lbn-opacity-slider" style="flex: 1;" />
                                                            <span class="lbn-opacity-value" style="min-width: 30px; text-align: center; font-weight: 600;">
                                                                <?php echo esc_html(isset($options['background_overlay_opacity']) ? $options['background_overlay_opacity'] : 0.3); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="lbn-description">
                                                    <?php _e('Add a colored overlay on top of the background image to improve text readability. Adjust the opacity to control the overlay intensity.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="lost_password_color"><?php _e('Lost Password Link Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="lost_password_color" name="lbn_settings[lost_password_color]" 
                                                       value="<?php echo esc_attr(isset($options['lost_password_color']) ? $options['lost_password_color'] : '#50575e'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="lost_password_color_text" 
                                                       value="<?php echo esc_attr(isset($options['lost_password_color']) ? $options['lost_password_color'] : '#50575e'); ?>" 
                                                       class="lbn-color-text" readonly />
                                                <p class="lbn-description">
                                                    <?php _e('Choose the text color for the "Lost your password?" link.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="back_to_site_color"><?php _e('Back to Site Link Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="back_to_site_color" name="lbn_settings[back_to_site_color]" 
                                                       value="<?php echo esc_attr(isset($options['back_to_site_color']) ? $options['back_to_site_color'] : '#50575e'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="back_to_site_color_text" 
                                                       value="<?php echo esc_attr(isset($options['back_to_site_color']) ? $options['back_to_site_color'] : '#50575e'); ?>" 
                                                       class="lbn-color-text" readonly />
                                                <p class="lbn-description">
                                                    <?php _e('Choose the text color for the "Go to starter-theme" link.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Styling Tab -->
                    <div class="lbn-tab-panel" id="form-styling-panel">
                        <div class="lbn-settings-card">
                            <div class="lbn-card-body">
                                <table class="lbn-form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="form_background_color"><?php _e('Form Background Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="form_background_color" name="lbn_settings[form_background_color]" 
                                                       value="<?php echo esc_attr(isset($options['form_background_color']) ? $options['form_background_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="form_background_color_text" 
                                                       value="<?php echo esc_attr(isset($options['form_background_color']) ? $options['form_background_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-text" readonly />
                                                <p class="lbn-description">
                                                    <?php _e('Choose the background color for the login form.', 'login-branding-notice'); ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_background_opacity"><?php _e('Form Background Opacity', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <input type="range" id="form_background_opacity" name="lbn_settings[form_background_opacity]" 
                                                       min="0" max="1" step="0.1" 
                                                       value="<?php echo esc_attr(isset($options['form_background_opacity']) ? $options['form_background_opacity'] : 1); ?>" 
                                                       class="lbn-opacity-slider" style="flex: 1;" />
                                                <span class="lbn-opacity-value" style="min-width: 30px; text-align: center; font-weight: 600;">
                                                    <?php echo esc_html(isset($options['form_background_opacity']) ? $options['form_background_opacity'] : 1); ?>
                                                </span>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Adjust the opacity of the form background. 0 = transparent, 1 = fully opaque.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_border_radius"><?php _e('Form Border Radius', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <input type="number" id="form_border_radius" name="lbn_settings[form_border_radius]" 
                                                       value="<?php echo esc_attr(isset($options['form_border_radius']) ? $options['form_border_radius'] : 0); ?>" 
                                                       min="0" max="50" class="lbn-input" style="width: 80px;" />
                                                <span style="color: #666;">px</span>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Set the border radius for the login form corners. 0 = square corners, higher values = more rounded.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_shadow"><?php _e('Form Shadow', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <label class="lbn-toggle">
                                                <input type="checkbox" id="form_shadow" name="lbn_settings[form_shadow]" 
                                                       value="1" <?php checked(1, isset($options['form_shadow']) ? $options['form_shadow'] : 0); ?> />
                                                <span class="lbn-toggle-slider"></span>
                                            </label>
                                            <p class="lbn-description">
                                                <?php _e('Add a subtle shadow effect around the login form for better visual depth.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_text_color"><?php _e('Form Text Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="form_text_color" name="lbn_settings[form_text_color]" 
                                                       value="<?php echo esc_attr(isset($options['form_text_color']) ? $options['form_text_color'] : '#333333'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="form_text_color_text" 
                                                       value="<?php echo esc_attr(isset($options['form_text_color']) ? $options['form_text_color'] : '#333333'); ?>" 
                                                       class="lbn-color-text" readonly />
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Choose the text color for labels and text inside the login form.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_font_family"><?php _e('Form Font Family', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $font_family = isset($options['form_font_family']) ? $options['form_font_family'] : 'default';
                                            $fonts = array(
                                                'default' => __('Default (WordPress)', 'login-branding-notice'),
                                                'Arial, sans-serif' => 'Arial',
                                                'Helvetica, Arial, sans-serif' => 'Helvetica',
                                                '"Times New Roman", Times, serif' => 'Times New Roman',
                                                'Georgia, serif' => 'Georgia',
                                                '"Courier New", Courier, monospace' => 'Courier New',
                                                'Verdana, Geneva, sans-serif' => 'Verdana',
                                                'Tahoma, Geneva, sans-serif' => 'Tahoma',
                                                '"Trebuchet MS", Helvetica, sans-serif' => 'Trebuchet MS',
                                                '"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans',
                                                '"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
                                                '"Open Sans", sans-serif' => 'Open Sans',
                                                '"Roboto", sans-serif' => 'Roboto',
                                                '"Lato", sans-serif' => 'Lato',
                                                '"Montserrat", sans-serif' => 'Montserrat',
                                                '"Source Sans Pro", sans-serif' => 'Source Sans Pro'
                                            );
                                            ?>
                                            <select id="form_font_family" name="lbn_settings[form_font_family]" class="lbn-select">
                                                <?php foreach ($fonts as $value => $label): ?>
                                                <option value="<?php echo esc_attr($value); ?>" <?php selected($font_family, $value); ?>>
                                                    <?php echo esc_html($label); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p class="lbn-description">
                                                <?php _e('Select the font family for the login form text. Google Fonts require additional setup.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_font_size"><?php _e('Form Font Size', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <input type="number" id="form_font_size" name="lbn_settings[form_font_size]" 
                                                       value="<?php echo esc_attr(isset($options['form_font_size']) ? $options['form_font_size'] : 14); ?>" 
                                                       min="8" max="32" class="lbn-input" style="width: 80px;" />
                                                <span style="color: #666;">px</span>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Set the font size for the login form text. Range: 8-32 pixels.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="form_font_weight"><?php _e('Form Font Weight', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $font_weight = isset($options['form_font_weight']) ? $options['form_font_weight'] : 'normal';
                                            $weights = array(
                                                'normal' => __('Normal (400)', 'login-branding-notice'),
                                                'bold' => __('Bold (700)', 'login-branding-notice'),
                                                '300' => __('Light (300)', 'login-branding-notice'),
                                                '500' => __('Medium (500)', 'login-branding-notice'),
                                                '600' => __('Semi Bold (600)', 'login-branding-notice'),
                                                '800' => __('Extra Bold (800)', 'login-branding-notice')
                                            );
                                            ?>
                                            <select id="form_font_weight" name="lbn_settings[form_font_weight]" class="lbn-select">
                                                <?php foreach ($weights as $value => $label): ?>
                                                <option value="<?php echo esc_attr($value); ?>" <?php selected($font_weight, $value); ?>>
                                                    <?php echo esc_html($label); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p class="lbn-description">
                                                <?php _e('Choose the font weight (boldness) for the login form text.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Button Styling Section -->
                        <div class="lbn-settings-card">
                            <h3 class="lbn-section-title">
                                <span class="dashicons dashicons-button" style="margin-right: 8px;"></span>
                                <?php _e('Button Styling', 'login-branding-notice'); ?>
                            </h3>
                            <div class="lbn-card-body">
                                <table class="lbn-form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="button_background_color"><?php _e('Button Background Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="button_background_color" name="lbn_settings[button_background_color]" 
                                                       value="<?php echo esc_attr(isset($options['button_background_color']) ? $options['button_background_color'] : '#0073aa'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="button_background_color_text" 
                                                       value="<?php echo esc_attr(isset($options['button_background_color']) ? $options['button_background_color'] : '#0073aa'); ?>" 
                                                       class="lbn-color-text" readonly />
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Choose the background color for the login button.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_text_color"><?php _e('Button Text Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="button_text_color" name="lbn_settings[button_text_color]" 
                                                       value="<?php echo esc_attr(isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="button_text_color_text" 
                                                       value="<?php echo esc_attr(isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-text" readonly />
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Choose the text color for the login button.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_hover_background_color"><?php _e('Button Hover Background Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="button_hover_background_color" name="lbn_settings[button_hover_background_color]" 
                                                       value="<?php echo esc_attr(isset($options['button_hover_background_color']) ? $options['button_hover_background_color'] : '#005a87'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="button_hover_background_color_text" 
                                                       value="<?php echo esc_attr(isset($options['button_hover_background_color']) ? $options['button_hover_background_color'] : '#005a87'); ?>" 
                                                       class="lbn-color-text" readonly />
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Choose the background color for the login button when hovered.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_hover_text_color"><?php _e('Button Hover Text Color', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div class="lbn-color-field">
                                                <input type="color" id="button_hover_text_color" name="lbn_settings[button_hover_text_color]" 
                                                       value="<?php echo esc_attr(isset($options['button_hover_text_color']) ? $options['button_hover_text_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-picker" />
                                                <input type="text" id="button_hover_text_color_text" 
                                                       value="<?php echo esc_attr(isset($options['button_hover_text_color']) ? $options['button_hover_text_color'] : '#ffffff'); ?>" 
                                                       class="lbn-color-text" readonly />
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Choose the text color for the login button when hovered.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_border_radius"><?php _e('Button Border Radius', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <input type="number" id="button_border_radius" name="lbn_settings[button_border_radius]" 
                                                       value="<?php echo esc_attr(isset($options['button_border_radius']) ? $options['button_border_radius'] : 3); ?>" 
                                                       min="0" max="50" class="lbn-input" style="width: 80px;" />
                                                <span style="color: #666;">px</span>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Set the border radius for the login button. Range: 0-50 pixels.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_font_size"><?php _e('Button Font Size', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <input type="number" id="button_font_size" name="lbn_settings[button_font_size]" 
                                                       value="<?php echo esc_attr(isset($options['button_font_size']) ? $options['button_font_size'] : 14); ?>" 
                                                       min="8" max="32" class="lbn-input" style="width: 80px;" />
                                                <span style="color: #666;">px</span>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Set the font size for the login button text. Range: 8-32 pixels.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="button_font_weight"><?php _e('Button Font Weight', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $button_font_weight = isset($options['button_font_weight']) ? $options['button_font_weight'] : 'normal';
                                            $weights = array(
                                                'normal' => __('Normal (400)', 'login-branding-notice'),
                                                'bold' => __('Bold (700)', 'login-branding-notice'),
                                                '300' => __('Light (300)', 'login-branding-notice'),
                                                '500' => __('Medium (500)', 'login-branding-notice'),
                                                '600' => __('Semi Bold (600)', 'login-branding-notice'),
                                                '800' => __('Extra Bold (800)', 'login-branding-notice')
                                            );
                                            ?>
                                            <select id="button_font_weight" name="lbn_settings[button_font_weight]" class="lbn-select">
                                                <?php foreach ($weights as $value => $label): ?>
                                                <option value="<?php echo esc_attr($value); ?>" <?php selected($button_font_weight, $value); ?>>
                                                    <?php echo esc_html($label); ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p class="lbn-description">
                                                <?php _e('Choose the font weight (boldness) for the login button text.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notice Settings Tab -->
                    <div class="lbn-tab-panel" id="notices-panel">
                        <div class="lbn-settings-card">
                            <div class="lbn-card-body">
                                <table class="lbn-form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="notice_enabled"><?php _e('Enable Notice', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <label class="lbn-toggle">
                                                <input type="checkbox" id="notice_enabled" name="lbn_settings[notice_enabled]" 
                                                       value="1" <?php checked(1, isset($options['notice_enabled']) ? $options['notice_enabled'] : 0); ?> />
                                                <span class="lbn-toggle-slider"></span>
                                            </label>
                                            <p class="lbn-description">
                                                <?php _e('Enable or disable the notice display on the login screen.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="notice-content-row" style="<?php echo empty($options['notice_enabled']) ? 'opacity: 0.5;' : ''; ?>">
                                        <th scope="row">
                                            <label for="notice_content"><?php _e('Notice Content', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $content = isset($options['notice_content']) ? $options['notice_content'] : '';
                                            wp_editor($content, 'notice_content', array(
                                                'textarea_name' => 'lbn_settings[notice_content]',
                                                'textarea_rows' => 8,
                                                'media_buttons' => true,
                                                'teeny' => false,
                                                'tinymce' => true,
                                                'quicktags' => true,
                                                'editor_class' => 'lbn-editor'
                                            ));
                                            ?>
                                            <p class="lbn-description">
                                                <?php _e('Enter the notice content to display on the login screen. You can use the rich editor to format text, add links, and insert media.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="notice-dates-row" style="<?php echo empty($options['notice_enabled']) ? 'opacity: 0.5;' : ''; ?>">
                                        <th scope="row">
                                            <label><?php _e('Display Dates', 'login-branding-notice'); ?></label>
                                        </th>
                                        <td>
                                            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                                                <div>
                                                    <label for="notice_start_date" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                                        <?php _e('Start Date:', 'login-branding-notice'); ?>
                                                    </label>
                                                    <input type="text" id="notice_start_date" name="lbn_settings[notice_start_date]" 
                                                           value="<?php echo esc_attr(isset($options['notice_start_date']) ? $options['notice_start_date'] : ''); ?>" 
                                                           class="lbn-input lbn-date-field" placeholder="YYYY-MM-DD" style="max-width: 150px;" />
                                                </div>
                                                <div>
                                                    <label for="notice_end_date" style="display: block; margin-bottom: 5px; font-weight: 600;">
                                                        <?php _e('End Date:', 'login-branding-notice'); ?>
                                                    </label>
                                                    <input type="text" id="notice_end_date" name="lbn_settings[notice_end_date]" 
                                                           value="<?php echo esc_attr(isset($options['notice_end_date']) ? $options['notice_end_date'] : ''); ?>" 
                                                           class="lbn-input lbn-date-field" placeholder="YYYY-MM-DD" style="max-width: 150px;" />
                                                </div>
                                            </div>
                                            <p class="lbn-description">
                                                <?php _e('Leave dates empty to display the notice permanently when enabled. Use YYYY-MM-DD format.', 'login-branding-notice'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div> <!-- End tab content -->
                        
                        <div style="text-align: center; margin-top: 30px;">
                            <button type="submit" class="lbn-save-btn">
                                <span class="dashicons dashicons-yes" style="margin-right: 8px;"></span>
                                <?php _e('Save All Settings', 'login-branding-notice'); ?>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="lbn-sidebar">
                    <div class="lbn-sidebar-card">
                        <div class="lbn-sidebar-header">
                            <span class="dashicons dashicons-visibility" style="margin-right: 8px;"></span>
                            <?php _e('Quick Actions', 'login-branding-notice'); ?>
                        </div>
                        <div class="lbn-sidebar-body">
                            <a href="<?php echo wp_login_url(); ?>" target="_blank" class="lbn-action-btn primary">
                                <span class="dashicons dashicons-external" style="margin-right: 8px;"></span>
                                <?php _e('Preview Login Page', 'login-branding-notice'); ?>
                            </a>
                            <button type="button" id="preview-notice" class="lbn-action-btn">
                                <span class="dashicons dashicons-visibility" style="margin-right: 8px;"></span>
                                <?php _e('Preview Notice', 'login-branding-notice'); ?>
                            </button>
                        </div>
                    </div>
                    
                    <div class="lbn-sidebar-card">
                        <div class="lbn-sidebar-header">
                            <span class="dashicons dashicons-info" style="margin-right: 8px;"></span>
                            <?php _e('Plugin Information', 'login-branding-notice'); ?>
                        </div>
                        <div class="lbn-sidebar-body">
                            <ul class="lbn-info-list">
                                <li>
                                    <span class="lbn-info-label"><?php _e('Version', 'login-branding-notice'); ?></span>
                                    <span class="lbn-info-value"><?php echo lbn_get_plugin_version(); ?></span>
                                </li>
                                <li>
                                    <span class="lbn-info-label"><?php _e('Status', 'login-branding-notice'); ?></span>
                                    <span class="lbn-status active"><?php _e('Active', 'login-branding-notice'); ?></span>
                                </li>
                                <li>
                                    <span class="lbn-info-label"><?php _e('WordPress', 'login-branding-notice'); ?></span>
                                    <span class="lbn-info-value">5.0+</span>
                                </li>
                                <li>
                                    <span class="lbn-info-label"><?php _e('PHP', 'login-branding-notice'); ?></span>
                                    <span class="lbn-info-value">7.4+</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="lbn-sidebar-card">
                        <div class="lbn-sidebar-header">
                            <span class="dashicons dashicons-heart" style="margin-right: 8px;"></span>
                            <?php _e('Support', 'login-branding-notice'); ?>
                        </div>
                        <div class="lbn-sidebar-body">
                            <p style="color: #646970; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                                <?php _e('Need help? Check out our documentation or contact support.', 'login-branding-notice'); ?>
                            </p>
                            <a href="#" class="lbn-action-btn" onclick="alert('Documentation coming soon!');">
                                <span class="dashicons dashicons-book" style="margin-right: 8px;"></span>
                                <?php _e('Documentation', 'login-branding-notice'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="notice-preview-modal">
                <div class="notice-preview-content">
                    <div class="notice-preview-header">
                        <h3>
                            <span class="dashicons dashicons-visibility" style="margin-right: 10px;"></span>
                            <?php _e('Notice Preview', 'login-branding-notice'); ?>
                        </h3>
                        <button class="close-preview" type="button">&times;</button>
                    </div>
                    <div id="notice-preview-content"></div>
                </div>
            </div>
            
            <!-- Custom Copyright Footer -->
            <div class="lbn-copyright-footer">
                <div class="lbn-copyright-content">
                    <p>
                        <span class="dashicons dashicons-admin-appearance" style="margin-right: 5px; color: #667eea;"></span>
                        <?php printf(
                            __('Login Branding Plugin &copy; %s - Crafted with %s for WordPress', 'login-branding-notice'),
                            date('Y'),
                            '<span class="dashicons dashicons-heart" style="color: #e74c3c; font-size: 14px; margin: 0 3px;"></span>'
                        ); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Display admin notices
     */
    public function admin_notices() {
        // Check requirements
        $requirements_check = lbn_check_requirements();
        if ($requirements_check !== true) {
            foreach ($requirements_check as $error) {
                echo '<div class="notice notice-error"><p>' . esc_html($error) . '</p></div>';
            }
        }
        
        // Show success message after settings save
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
            $current_screen = get_current_screen();
            if ($current_screen && $current_screen->id === 'settings_page_login-branding-notice') {
                echo '<div class="notice notice-success is-dismissible"><p>' . 
                     __('Login branding settings saved successfully!', 'login-branding-notice') . 
                     '</p></div>';
            }
        }
    }
    
    /**
     * AJAX handler for notice preview
     */
    public function ajax_preview_notice() {
        // Check nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'], 'lbn_preview_nonce') || !current_user_can('manage_options')) {
            wp_die(__('Security check failed', 'login-branding-notice'));
        }
        
        $content = wp_kses_post($_POST['content']);
        
        if (empty($content)) {
            wp_send_json_error(__('No content provided', 'login-branding-notice'));
        }
        
        // Process content like WordPress would
        $processed_content = apply_filters('the_content', $content);
        
        wp_send_json_success($processed_content);
    }
    
    /**
     * Remove default admin footer text on plugin page
     */
    public function remove_admin_footer_text($text) {
        $current_screen = get_current_screen();
        if ($current_screen && $current_screen->id === 'toplevel_page_login-branding-notice') {
            return '';
        }
        return $text;
    }
    
    /**
     * Remove WordPress version footer on plugin page
     */
    public function remove_update_footer($text) {
        $current_screen = get_current_screen();
        if ($current_screen && $current_screen->id === 'toplevel_page_login-branding-notice') {
            return '';
        }
        return $text;
    }
}