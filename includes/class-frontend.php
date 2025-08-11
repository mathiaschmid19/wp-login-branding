<?php
/**
 * Frontend functionality for Login Branding Notice plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LBN_Frontend {
    
    private $settings;
    
    public function __construct() {
        $this->settings = get_option('lbn_settings', array());
        
        // Login page hooks
        add_action('login_enqueue_scripts', array($this, 'login_enqueue_scripts'));
        add_action('login_headerurl', array($this, 'login_logo_url'));
        add_action('login_headertext', array($this, 'login_logo_title'));
        add_action('login_form', array($this, 'display_notice'));
        add_action('login_footer', array($this, 'login_footer_notice'));
    }
    
    /**
     * Enqueue login page styles and scripts
     */
    public function login_enqueue_scripts() {
        // Enqueue the main CSS file
        wp_enqueue_style(
            'lbn-login-styles',
            LBN_PLUGIN_URL . 'assets/css/login-styles.css',
            array('login'),
            LBN_VERSION
        );
        
        // Get custom CSS
        $custom_css = $this->generate_custom_css();
        
        if (!empty($custom_css)) {
            wp_add_inline_style('lbn-login-styles', $custom_css);
        }
        
        // Enqueue jQuery for interactions
        wp_enqueue_script('jquery');
        
        // Add custom JavaScript
        wp_add_inline_script('jquery', $this->get_login_js());
    }
    
    /**
     * Generate custom CSS for login page
     */
    private function generate_custom_css() {
        $css = '';
        
        // Custom logo styles
        if (!empty($this->settings['custom_logo'])) {
            $css .= '
                body.login div#login h1 a {
                    background-image: url(' . esc_url($this->settings['custom_logo']) . ');
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center center;
                    width: 100%;
                    height: 80px;
                    padding-bottom: 20px;
                }
            ';
        }
        
        // Custom background styles
        $background_color = !empty($this->settings['background_color']) ? $this->settings['background_color'] : '#f1f1f1';
        $background_image = !empty($this->settings['background_image']) ? $this->settings['background_image'] : '';
        $overlay_color = !empty($this->settings['background_overlay_color']) ? $this->settings['background_overlay_color'] : '#000000';
        $overlay_opacity = isset($this->settings['background_overlay_opacity']) ? $this->settings['background_overlay_opacity'] : 0.3;
        
        // Base background color
        $css .= '
            body.login {
                background-color: ' . esc_attr($background_color) . ';
            }
        ';
        
        // Background image if set
        if (!empty($background_image)) {
            $css .= '
                body.login {
                    background-image: url(' . esc_url($background_image) . ');
                    background-size: cover;
                    background-position: center center;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                }
            ';
        }
        
        // Background overlay if background image is set and overlay opacity > 0
        if (!empty($background_image) && $overlay_opacity > 0) {
            $rgba_color = $this->hex_to_rgba($overlay_color, $overlay_opacity);
            $css .= '
                body.login::before {
                    content: "";
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: ' . $rgba_color . ';
                    z-index: -1;
                    pointer-events: none;
                }
            ';
        }
        
        // Link colors
        $lost_password_color = !empty($this->settings['lost_password_color']) ? $this->settings['lost_password_color'] : '#50575e';
        $back_to_site_color = !empty($this->settings['back_to_site_color']) ? $this->settings['back_to_site_color'] : '#50575e';
        
        $css .= '
            #nav a {
                color: ' . esc_attr($lost_password_color) . ' !important;
            }
            
            #nav a:hover,
            #nav a:focus {
                color: ' . esc_attr($this->adjust_brightness($lost_password_color, -20)) . ' !important;
            }
            
            #backtoblog a {
                color: ' . esc_attr($back_to_site_color) . ' !important;
            }
            
            #backtoblog a:hover,
            #backtoblog a:focus {
                color: ' . esc_attr($this->adjust_brightness($back_to_site_color, -20)) . ' !important;
            }
        ';
        
        // Form styling
        $form_bg_color = !empty($this->settings['form_background_color']) ? $this->settings['form_background_color'] : '#ffffff';
        $form_bg_opacity = isset($this->settings['form_background_opacity']) ? floatval($this->settings['form_background_opacity']) : 1;
        $form_border_radius = isset($this->settings['form_border_radius']) ? intval($this->settings['form_border_radius']) : 0;
        $form_shadow = !empty($this->settings['form_shadow']);
        
        // Convert hex color to rgba with opacity
        $form_bg_rgba = $this->hex_to_rgba($form_bg_color, $form_bg_opacity);
        
        $css .= '
            #login form {
                background-color: ' . esc_attr($form_bg_rgba) . ' !important;
                border-radius: ' . esc_attr($form_border_radius) . 'px !important;
        ';
        
        if ($form_shadow) {
            $css .= '
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            ';
        }
        
        $css .= '
            }
        ';
        
        // Form text styling
        $form_text_color = !empty($this->settings['form_text_color']) ? $this->settings['form_text_color'] : '#333333';
        $form_font_family = !empty($this->settings['form_font_family']) ? $this->settings['form_font_family'] : 'default';
        $form_font_size = !empty($this->settings['form_font_size']) ? intval($this->settings['form_font_size']) : 14;
        $form_font_weight = !empty($this->settings['form_font_weight']) ? $this->settings['form_font_weight'] : 'normal';
        
        $css .= '
            #login form label,
            #login form .forgetmenot label,
            #login form p,
            #login form .input,
            #login form input[type="text"],
            #login form input[type="password"],
            #login form input[type="email"] {
                color: ' . esc_attr($form_text_color) . ' !important;
                font-size: ' . esc_attr($form_font_size) . 'px !important;
                font-weight: ' . esc_attr($form_font_weight) . ' !important;
        ';
        
        if ($form_font_family !== 'default') {
            $css .= '
                font-family: ' . esc_attr($form_font_family) . ' !important;
            ';
        }
        
        $css .= '
            }
            
            #login form .forgetmenot {
                color: ' . esc_attr($form_text_color) . ' !important;
                font-size: ' . esc_attr($form_font_size) . 'px !important;
                font-weight: ' . esc_attr($form_font_weight) . ' !important;
        ';
        
        if ($form_font_family !== 'default') {
            $css .= '
                font-family: ' . esc_attr($form_font_family) . ' !important;
            ';
        }
        
        $css .= '
            }
        ';
        
        // Button styling
        $button_bg_color = !empty($this->settings['button_background_color']) ? $this->settings['button_background_color'] : '#0073aa';
        $button_text_color = !empty($this->settings['button_text_color']) ? $this->settings['button_text_color'] : '#ffffff';
        $button_hover_bg_color = !empty($this->settings['button_hover_background_color']) ? $this->settings['button_hover_background_color'] : '#005a87';
        $button_hover_text_color = !empty($this->settings['button_hover_text_color']) ? $this->settings['button_hover_text_color'] : '#ffffff';
        $button_border_radius = isset($this->settings['button_border_radius']) ? intval($this->settings['button_border_radius']) : 3;
        $button_font_size = !empty($this->settings['button_font_size']) ? intval($this->settings['button_font_size']) : 14;
        $button_font_weight = !empty($this->settings['button_font_weight']) ? $this->settings['button_font_weight'] : 'normal';
        
        $css .= '
            #login form .submit input[type="submit"],
            #login form input[type="submit"] {
                background-color: ' . esc_attr($button_bg_color) . ' !important;
                color: ' . esc_attr($button_text_color) . ' !important;
                border-radius: ' . esc_attr($button_border_radius) . 'px !important;
                font-size: ' . esc_attr($button_font_size) . 'px !important;
                font-weight: ' . esc_attr($button_font_weight) . ' !important;
                border: none !important;
                box-shadow: none !important;
                text-shadow: none !important;
                transition: all 0.3s ease !important;
            }
            
            #login form .submit input[type="submit"]:hover,
            #login form .submit input[type="submit"]:focus,
            #login form input[type="submit"]:hover,
            #login form input[type="submit"]:focus {
                background-color: ' . esc_attr($button_hover_bg_color) . ' !important;
                color: ' . esc_attr($button_hover_text_color) . ' !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
        ';
        
        // Notice styles
        $css .= '
            .lbn-notice {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 15px;
                margin: 20px 0;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                animation: fadeIn 0.5s ease-in;
            }
            
            .lbn-notice h3 {
                margin-top: 0;
                color: #333;
            }
            
            .lbn-notice p {
                margin-bottom: 10px;
                line-height: 1.5;
            }
            
            .lbn-notice:last-child p:last-child {
                margin-bottom: 0;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .lbn-notice-footer {
                margin-top: 20px;
                text-align: center;
            }
            
            .lbn-notice-footer .lbn-notice {
                display: inline-block;
                text-align: left;
                max-width: 400px;
            }
        ';
        
        return $css;
    }
    
    /**
     * Get custom JavaScript for login page
     */
    private function get_login_js() {
        return '
            jQuery(document).ready(function($) {
                // Add smooth transitions
                $(".lbn-notice").hide().fadeIn(600);
                
                // Add click to dismiss functionality
                $(".lbn-notice").prepend("<button type=\"button\" class=\"notice-dismiss\" style=\"float: right; background: none; border: none; cursor: pointer; font-size: 18px; color: #666;\">&times;</button>");
                
                $(".notice-dismiss").on("click", function() {
                    $(this).parent().fadeOut(300);
                });
            });
        ';
    }
    
    /**
     * Set custom logo URL
     */
    public function login_logo_url() {
        return home_url();
    }
    
    /**
     * Set custom logo title
     */
    public function login_logo_title() {
        return get_bloginfo('name');
    }
    
    /**
     * Display notice on login form
     */
    public function display_notice() {
        if (!$this->should_display_notice()) {
            return;
        }
        
        $notice_content = $this->get_notice_content();
        
        if (!empty($notice_content)) {
            echo '<div class="lbn-notice">' . wp_kses_post($notice_content) . '</div>';
        }
    }
    
    /**
     * Display notice in login footer
     */
    public function login_footer_notice() {
        if (!$this->should_display_notice()) {
            return;
        }
        
        $notice_content = $this->get_notice_content();
        
        if (!empty($notice_content)) {
            echo '<div class="lbn-notice-footer">';
            echo '<div class="lbn-notice">' . wp_kses_post($notice_content) . '</div>';
            echo '</div>';
        }
    }
    
    /**
     * Check if notice should be displayed
     */
    private function should_display_notice() {
        // Check if notices are enabled
        if (empty($this->settings['notice_enabled'])) {
            return false;
        }
        
        // Check if there's content
        if (empty($this->settings['notice_content'])) {
            return false;
        }
        
        // Check date range
        $start_date = !empty($this->settings['notice_start_date']) ? $this->settings['notice_start_date'] : '';
        $end_date = !empty($this->settings['notice_end_date']) ? $this->settings['notice_end_date'] : '';
        
        if (!empty($start_date) || !empty($end_date)) {
            $current_date = current_time('Y-m-d');
            
            // Check start date
            if (!empty($start_date) && $current_date < $start_date) {
                return false;
            }
            
            // Check end date
            if (!empty($end_date) && $current_date > $end_date) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get notice content
     */
    private function get_notice_content() {
        if (empty($this->settings['notice_content'])) {
            return '';
        }
        
        // Apply WordPress filters to content
        $content = apply_filters('the_content', $this->settings['notice_content']);
        
        // Remove empty paragraphs
        $content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $content);
        
        return $content;
    }
    
    /**
     * Convert hex color to RGBA
     */
    private function hex_to_rgba($hex, $alpha = 1) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert 3-digit hex to 6-digit
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "rgba($r, $g, $b, $alpha)";
    }
    
    /**
     * Adjust brightness of a hex color
     */
    private function adjust_brightness($hex, $percent) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert 3-digit hex to 6-digit
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        // Convert hex to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Adjust brightness
        $r = max(0, min(255, $r + ($r * $percent / 100)));
        $g = max(0, min(255, $g + ($g * $percent / 100)));
        $b = max(0, min(255, $b + ($b * $percent / 100)));
        
        // Convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}