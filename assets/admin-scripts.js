/**
 * Login Branding Notice - Admin Scripts
 */

jQuery(document).ready(function($) {
    // Initialize the beautiful interface
    initializeInterface();
    
    // Media uploader for images
    $('.upload-image').click(function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetField = button.data('target');
        
        var mediaUploader = wp.media({
            title: lbnAdmin.strings.uploadTitle || 'Choose Image',
            button: {
                text: lbnAdmin.strings.selectImage || 'Select Image'
            },
            library: {
                type: 'image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#' + targetField).val(attachment.url);
            
            // Show preview with animation
            var preview = $('#' + targetField + '_preview');
            if (preview.length) {
                preview.attr('src', attachment.url).addClass('has-image').fadeIn(300);
            }
            
            // Show remove button with animation
            var removeBtn = button.siblings('.remove-image');
            if (removeBtn.length === 0) {
                removeBtn = $('<button type="button" class="lbn-remove-btn remove-image" data-target="' + targetField + '">Remove</button>');
                button.after(removeBtn);
                bindRemoveEvents(removeBtn);
            }
            removeBtn.fadeIn(300);
            
            // Show success message
            showSuccessMessage(button.closest('.lbn-image-upload'), 'Image uploaded successfully!');
        });
        
        mediaUploader.open();
    });
    
    // Bind remove events to existing and new buttons
    bindRemoveEvents($('.remove-image'));
    
    function bindRemoveEvents(elements) {
        elements.off('click').on('click', function(e) {
            e.preventDefault();
            
            if (confirm(lbnAdmin.strings.confirmRemove || 'Are you sure you want to remove this image?')) {
                var button = $(this);
                var targetField = button.data('target');
                
                // Clear the input
                $('#' + targetField).val('');
                
                // Hide preview with animation
                var preview = $('#' + targetField + '_preview');
                if (preview.length) {
                    preview.fadeOut(300, function() {
                        $(this).removeClass('has-image').attr('src', '');
                    });
                }
                
                // Hide remove button with animation
                button.fadeOut(300);
                
                // Show success message
                showSuccessMessage(button.closest('.lbn-image-upload'), 'Image removed successfully!');
            }
        });
    }
    
    // Date pickers
    $('.lbn-date-field').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '-1:+5'
    });
    
    // Toggle notice settings visibility with smooth animation
    $('#notice_enabled').change(function() {
        var isChecked = $(this).is(':checked');
        var noticeRows = $('.notice-content-row, .notice-dates-row');
        
        if (isChecked) {
            noticeRows.animate({opacity: 1}, 300);
        } else {
            noticeRows.animate({opacity: 0.5}, 300);
        }
    });
    
    // Color picker functionality
    $('.lbn-color-picker').on('input change', function() {
        var colorValue = $(this).val();
        var textInput = $(this).siblings('.lbn-color-text');
        textInput.val(colorValue);
    });
    
    $('.lbn-color-text').on('input', function() {
        var colorValue = $(this).val();
        var colorPicker = $(this).siblings('.lbn-color-picker');
        
        // Validate hex color format
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(colorValue)) {
            colorPicker.val(colorValue);
            $(this).removeClass('error');
        } else {
            $(this).addClass('error');
        }
    });
    
    // Opacity slider functionality
    $('.lbn-opacity-slider').on('input', function() {
        var opacityValue = parseFloat($(this).val()).toFixed(2);
        var valueDisplay = $(this).siblings('.lbn-opacity-value');
        valueDisplay.text(opacityValue);
        
        // Update the hidden input if it exists
        var hiddenInput = $('input[name="lbn_settings[background_overlay_opacity]"]');
        if (hiddenInput.length) {
            hiddenInput.val(opacityValue);
        }
    });
    
    // Initialize opacity display on page load
    $('.lbn-opacity-slider').each(function() {
        var opacityValue = parseFloat($(this).val()).toFixed(2);
        var valueDisplay = $(this).siblings('.lbn-opacity-value');
        valueDisplay.text(opacityValue);
    });
    
    // Form styling color picker functionality
    $('#form_background_color').on('input change', function() {
        var colorValue = $(this).val();
        $('#form_background_color_text').val(colorValue);
    });
    
    $('#form_background_color_text').on('input', function() {
        var colorValue = $(this).val();
        
        // Validate hex color format
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(colorValue)) {
            $('#form_background_color').val(colorValue);
            $(this).removeClass('error');
        } else {
            $(this).addClass('error');
        }
    });
    
    // Form text color picker functionality
    $('#form_text_color').on('input change', function() {
        var colorValue = $(this).val();
        $('#form_text_color_text').val(colorValue);
    });
    
    $('#form_text_color_text').on('input', function() {
        var colorValue = $(this).val();
        
        // Validate hex color format
        if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(colorValue)) {
            $('#form_text_color').val(colorValue);
            $(this).removeClass('error');
        } else {
            $(this).addClass('error');
        }
    });
    
    // Form background opacity slider functionality
    $('#form_background_opacity').on('input', function() {
        var opacityValue = parseFloat($(this).val()).toFixed(1);
        $(this).siblings('.lbn-opacity-value').text(opacityValue);
    });
    
    // Initialize form background opacity display
    var formOpacityValue = parseFloat($('#form_background_opacity').val()).toFixed(1);
    $('#form_background_opacity').siblings('.lbn-opacity-value').text(formOpacityValue);
    

    
    // Notice preview functionality with enhanced modal
    $('#preview-notice').click(function() {
        var content = '';
        
        // Get content from TinyMCE if available
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get('notice_content')) {
            content = tinyMCE.get('notice_content').getContent();
        } else {
            content = $('#notice_content').val();
        }
        
        if (!content.trim()) {
            showNotification(lbnAdmin.strings.enterContent || 'Please enter notice content', 'warning');
            return;
        }
        
        // Show loading with animation
        $('#notice-preview-content').html('<div style="text-align: center; padding: 40px;"><span class="dashicons dashicons-update-alt" style="font-size: 24px; animation: spin 1s linear infinite;"></span><p style="margin-top: 15px;">' + (lbnAdmin.strings.loadingPreview || 'Loading preview...') + '</p></div>');
        $('#notice-preview-modal').fadeIn(300);
        
        // AJAX request to get preview
        $.post(ajaxurl, {
            action: 'lbn_preview_notice',
            content: content,
            nonce: lbnAdmin.previewNonce
        }, function(response) {
            if (response.success) {
                $('#notice-preview-content').html('<div class="lbn-notice">' + response.data + '</div>');
            } else {
                $('#notice-preview-content').html('<div style="text-align: center; padding: 40px; color: #dc3232;"><span class="dashicons dashicons-warning" style="font-size: 24px;"></span><p style="margin-top: 15px;">Error: ' + response.data + '</p></div>');
            }
        }).fail(function() {
            $('#notice-preview-content').html('<div style="text-align: center; padding: 40px; color: #dc3232;"><span class="dashicons dashicons-warning" style="font-size: 24px;"></span><p style="margin-top: 15px;">' + (lbnAdmin.strings.errorLoading || 'Error loading preview') + '</p></div>');
        });
    });
    
    // Close preview modal with animation
    $('.close-preview').click(function() {
        $('#notice-preview-modal').fadeOut(300);
    });
    
    // Close modal when clicking outside
    $('#notice-preview-modal').click(function(e) {
        if (e.target === this) {
            $(this).fadeOut(300);
        }
    });
    
    // Close modal with escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27 && $('#notice-preview-modal').is(':visible')) {
            $('#notice-preview-modal').fadeOut(300);
        }
    });
    
    // Enhanced form validation with better UX
    $('form').submit(function(e) {
        var isValid = true;
        var firstError = null;
        
        // Clear previous error states
        $('.lbn-input').removeClass('error');
        
        var logoUrl = $('#custom_logo').val();
        var bgUrl = $('#background_image').val();
        var bgColor = $('#background_color').val();
        var overlayColor = $('#background_overlay_color').val();
        var lostPasswordColor = $('#lost_password_color').val();
        var backToSiteColor = $('#back_to_site_color').val();
        
        // Validate URLs if provided
        if (logoUrl && !isValidUrl(logoUrl)) {
            $('#custom_logo').addClass('error');
            if (!firstError) firstError = $('#custom_logo');
            showNotification((lbnAdmin.strings.invalidLogoUrl || 'Invalid logo URL') + ': ' + logoUrl, 'error');
            isValid = false;
        }
        
        if (bgUrl && !isValidUrl(bgUrl)) {
            $('#background_image').addClass('error');
            if (!firstError) firstError = $('#background_image');
            showNotification((lbnAdmin.strings.invalidBgUrl || 'Invalid background URL') + ': ' + bgUrl, 'error');
            isValid = false;
        }
        
        // Validate color fields if provided
        if (bgColor && !isValidHexColor(bgColor)) {
            $('#background_color').addClass('error');
            if (!firstError) firstError = $('#background_color');
            showNotification((lbnAdmin.strings.invalidBgColor || 'Invalid background color') + ': ' + bgColor, 'error');
            isValid = false;
        }
        
        if (overlayColor && !isValidHexColor(overlayColor)) {
            $('#background_overlay_color').addClass('error');
            if (!firstError) firstError = $('#background_overlay_color');
            showNotification((lbnAdmin.strings.invalidOverlayColor || 'Invalid overlay color') + ': ' + overlayColor, 'error');
            isValid = false;
        }
        
        if (lostPasswordColor && !isValidHexColor(lostPasswordColor)) {
            $('#lost_password_color').addClass('error');
            if (!firstError) firstError = $('#lost_password_color');
            showNotification((lbnAdmin.strings.invalidLostPasswordColor || 'Invalid lost password link color') + ': ' + lostPasswordColor, 'error');
            isValid = false;
        }
        
        if (backToSiteColor && !isValidHexColor(backToSiteColor)) {
            $('#back_to_site_color').addClass('error');
            if (!firstError) firstError = $('#back_to_site_color');
            showNotification((lbnAdmin.strings.invalidBackToSiteColor || 'Invalid back to site link color') + ': ' + backToSiteColor, 'error');
            isValid = false;
        }
        
        // Validate form styling fields
        var formBgColor = $('#form_background_color').val();
        var formBgOpacity = $('#form_background_opacity').val();
        var formBorderRadius = $('#form_border_radius').val();
        
        if (formBgColor && !isValidHexColor(formBgColor)) {
            $('#form_background_color').addClass('error');
            if (!firstError) firstError = $('#form_background_color');
            showNotification((lbnAdmin.strings.invalidFormBgColor || 'Invalid form background color') + ': ' + formBgColor, 'error');
            isValid = false;
        }
        
        if (formBgOpacity && (isNaN(formBgOpacity) || formBgOpacity < 0 || formBgOpacity > 1)) {
            $('#form_background_opacity').addClass('error');
            if (!firstError) firstError = $('#form_background_opacity');
            showNotification((lbnAdmin.strings.invalidFormOpacity || 'Form background opacity must be between 0 and 1') + ': ' + formBgOpacity, 'error');
            isValid = false;
        }
        
        if (formBorderRadius && (isNaN(formBorderRadius) || formBorderRadius < 0)) {
            $('#form_border_radius').addClass('error');
            if (!firstError) firstError = $('#form_border_radius');
            showNotification((lbnAdmin.strings.invalidFormRadius || 'Form border radius must be a positive number') + ': ' + formBorderRadius, 'error');
            isValid = false;
        }
        
        // Validate form text color
        var formTextColor = $('#form_text_color').val();
        if (formTextColor && !isValidHexColor(formTextColor)) {
            $('#form_text_color').addClass('error');
            if (!firstError) firstError = $('#form_text_color');
            showNotification((lbnAdmin.strings.invalidFormTextColor || 'Invalid form text color') + ': ' + formTextColor, 'error');
            isValid = false;
        }
        
        // Validate form font size
        var formFontSize = $('#form_font_size').val();
        if (formFontSize && (isNaN(formFontSize) || formFontSize < 8 || formFontSize > 32)) {
            $('#form_font_size').addClass('error');
            if (!firstError) firstError = $('#form_font_size');
            showNotification((lbnAdmin.strings.invalidFormFontSize || 'Form font size must be between 8 and 32 pixels') + ': ' + formFontSize, 'error');
            isValid = false;
        }
        
        // Validate button colors
        var buttonBgColor = $('#button_background_color').val();
        var buttonTextColor = $('#button_text_color').val();
        var buttonHoverBgColor = $('#button_hover_background_color').val();
        var buttonHoverTextColor = $('#button_hover_text_color').val();
        
        if (buttonBgColor && !isValidHexColor(buttonBgColor)) {
            $('#button_background_color').addClass('error');
            if (!firstError) firstError = $('#button_background_color');
            showNotification('Please enter a valid hex color for button background', 'error');
            isValid = false;
        }
        
        if (buttonTextColor && !isValidHexColor(buttonTextColor)) {
            $('#button_text_color').addClass('error');
            if (!firstError) firstError = $('#button_text_color');
            showNotification('Please enter a valid hex color for button text', 'error');
            isValid = false;
        }
        
        if (buttonHoverBgColor && !isValidHexColor(buttonHoverBgColor)) {
            $('#button_hover_background_color').addClass('error');
            if (!firstError) firstError = $('#button_hover_background_color');
            showNotification('Please enter a valid hex color for button hover background', 'error');
            isValid = false;
        }
        
        if (buttonHoverTextColor && !isValidHexColor(buttonHoverTextColor)) {
            $('#button_hover_text_color').addClass('error');
            if (!firstError) firstError = $('#button_hover_text_color');
            showNotification('Please enter a valid hex color for button hover text', 'error');
            isValid = false;
        }
        
        // Validate button border radius
        var buttonBorderRadius = $('#button_border_radius').val();
        if (buttonBorderRadius && (isNaN(buttonBorderRadius) || parseInt(buttonBorderRadius) < 0)) {
            $('#button_border_radius').addClass('error');
            if (!firstError) firstError = $('#button_border_radius');
            showNotification('Button border radius must be a non-negative number', 'error');
            isValid = false;
        }
        
        // Validate button font size
        var buttonFontSize = $('#button_font_size').val();
        if (buttonFontSize && (isNaN(buttonFontSize) || parseInt(buttonFontSize) < 8 || parseInt(buttonFontSize) > 32)) {
            $('#button_font_size').addClass('error');
            if (!firstError) firstError = $('#button_font_size');
            showNotification('Button font size must be between 8 and 32 pixels', 'error');
            isValid = false;
        }
        
        // Validate dates if provided
        var startDate = $('#notice_start_date').val();
        var endDate = $('#notice_end_date').val();
        
        if (startDate && !isValidDate(startDate)) {
            $('#notice_start_date').addClass('error');
            if (!firstError) firstError = $('#notice_start_date');
            showNotification((lbnAdmin.strings.invalidStartDate || 'Invalid start date') + ': ' + startDate, 'error');
            isValid = false;
        }
        
        if (endDate && !isValidDate(endDate)) {
            $('#notice_end_date').addClass('error');
            if (!firstError) firstError = $('#notice_end_date');
            showNotification((lbnAdmin.strings.invalidEndDate || 'Invalid end date') + ': ' + endDate, 'error');
            isValid = false;
        }
        
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            $('#notice_start_date, #notice_end_date').addClass('error');
            if (!firstError) firstError = $('#notice_start_date');
            showNotification(lbnAdmin.strings.startAfterEnd || 'Start date cannot be after end date', 'error');
            isValid = false;
        }
        
        if (!isValid && firstError) {
            // Scroll to first error and focus
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
            firstError.focus();
            e.preventDefault();
            return false;
        }
        
        if (isValid) {
            // Show saving animation
            var saveBtn = $('.lbn-save-btn');
            saveBtn.html('<span class="dashicons dashicons-update-alt" style="animation: spin 1s linear infinite; margin-right: 8px;"></span>Saving...');
            saveBtn.prop('disabled', true);
        }
        
        return isValid;
    });
    
    // Helper functions
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    function isValidDate(dateString) {
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(dateString)) return false;
        
        var date = new Date(dateString);
        var timestamp = date.getTime();
        
        if (typeof timestamp !== 'number' || Number.isNaN(timestamp)) {
            return false;
        }
        
        return dateString === date.toISOString().split('T')[0];
    }
    
    function isValidHexColor(color) {
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color);
    }
    
    // Tab functionality
    function initializeTabs() {
        // Handle tab button clicks
        $('.lbn-tab-button').on('click', function(e) {
            e.preventDefault();
            
            var targetTab = $(this).data('tab');
            
            // Remove active class from all tabs and panels
            $('.lbn-tab-button').removeClass('active');
            $('.lbn-tab-panel').removeClass('active');
            
            // Add active class to clicked tab and corresponding panel
            $(this).addClass('active');
            $('#' + targetTab + '-panel').addClass('active');
            
            // Store active tab in localStorage for persistence
            localStorage.setItem('lbn_active_tab', targetTab);
            
            // Smooth scroll to top of tab content
            $('html, body').animate({
                scrollTop: $('.lbn-tab-navigation').offset().top - 50
            }, 300);
        });
        
        // Restore active tab from localStorage
        var activeTab = localStorage.getItem('lbn_active_tab');
        if (activeTab && $('#' + activeTab + '-panel').length) {
            $('.lbn-tab-button').removeClass('active');
            $('.lbn-tab-panel').removeClass('active');
            
            $('[data-tab="' + activeTab + '"]').addClass('active');
            $('#' + activeTab + '-panel').addClass('active');
        }
        
        // Handle URL hash for direct tab access
        if (window.location.hash) {
            var hashTab = window.location.hash.substring(1);
            if ($('#' + hashTab + '-panel').length) {
                $('.lbn-tab-button').removeClass('active');
                $('.lbn-tab-panel').removeClass('active');
                
                $('[data-tab="' + hashTab + '"]').addClass('active');
                $('#' + hashTab + '-panel').addClass('active');
                
                localStorage.setItem('lbn_active_tab', hashTab);
            }
        }
    }

    // Initialize interface enhancements
    function initializeInterface() {
        // Initialize tabs
        initializeTabs();
        
        // Add CSS for animations
        $('<style>')
            .prop('type', 'text/css')
            .html(`
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .lbn-input.error {
                    border-color: #dc3232 !important;
                    box-shadow: 0 0 0 3px rgba(220, 50, 50, 0.1) !important;
                }
                .lbn-notification {
                    position: fixed;
                    top: 32px;
                    right: 20px;
                    z-index: 999999;
                    max-width: 400px;
                    padding: 15px 20px;
                    border-radius: 6px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                }
                .lbn-notification.show {
                    transform: translateX(0);
                }
                .lbn-notification.success {
                    background: #d4edda;
                    color: #155724;
                    border-left: 4px solid #28a745;
                }
                .lbn-notification.error {
                    background: #f8d7da;
                    color: #721c24;
                    border-left: 4px solid #dc3232;
                }
                .lbn-notification.warning {
                    background: #fff3cd;
                    color: #856404;
                    border-left: 4px solid #ffc107;
                }
            `)
            .appendTo('head');
        
        // Hover effects removed per user request
    }
    
    // Show success message
    function showSuccessMessage(container, message) {
        var successMsg = $('<div class="lbn-success-message">' + message + '</div>');
        container.append(successMsg);
        setTimeout(function() {
            successMsg.addClass('show');
        }, 100);
        
        setTimeout(function() {
            successMsg.removeClass('show');
            setTimeout(function() {
                successMsg.remove();
            }, 300);
        }, 2000);
    }
    
    // Show notification
    function showNotification(message, type) {
        var notification = $('<div class="lbn-notification ' + type + '">' + message + '</div>');
        $('body').append(notification);
        
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 4000);
    }
    
    // Auto-save functionality (optional)
    var autoSaveTimeout;
    $('input, textarea').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Could implement auto-save here if needed
        }, 2000);
    });
    
});