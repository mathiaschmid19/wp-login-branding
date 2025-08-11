# Changelog

All notable changes to the Login Branding Notice plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-01

### Added
- Initial release of Login Branding Notice plugin
- Custom logo upload and display on login page
- Background image customization for login page
- Rich text notice editor with WYSIWYG functionality
- Scheduled notice display with start and end dates
- Notice preview functionality in admin
- Responsive design for mobile devices
- Dark mode support for notices
- **Beautiful LoginPress-style admin interface** with modern card-based layout
- **Professional grid-based admin layout** with responsive design
- Form validation and error handling
- Internationalization support (i18n)
- Security features including nonce verification
- Proper WordPress coding standards compliance
- Comprehensive documentation

### Enhanced
- **Complete admin interface redesign** inspired by LoginPress
- **Modern card-based layout** with clean sections for different settings
- **Enhanced visual hierarchy** with proper spacing and typography
- **Interactive elements** with hover effects and smooth animations
- **Improved form validation** with inline error states and notifications
- **Better image upload experience** with preview animations
- Image management with delete functionality for custom logo and background images
- Improved remove buttons with confirmation dialogs and visual feedback
- Enhanced background image styling with cover and center positioning
- Better user experience with animated feedback for image removal
- **Professional sidebar** with quick actions and plugin information
- **Responsive design** that works beautifully on all screen sizes

### Features
- **Branding Customization**
  - Upload custom logo with preview
  - Set background image for login page
  - Automatic image optimization and responsive display

- **Notice Management**
  - Rich text editor for notice content
  - Date-based scheduling (start/end dates)
  - Enable/disable notice functionality
  - Live preview of notices

- **Admin Interface**
  - Clean, modern admin interface
  - Sidebar with quick actions and plugin info
  - Form validation with user-friendly error messages
  - Media uploader integration

- **Technical Features**
  - WordPress 5.0+ compatibility
  - PHP 7.4+ requirement
  - Proper sanitization and validation
  - Security best practices
  - Internationalization ready
  - Uninstall cleanup

### Security
- All user inputs are properly sanitized
- Nonce verification for all forms and AJAX requests
- Capability checks for admin functions
- No sensitive data exposure in frontend

### Performance
- Minimal impact on site performance
- CSS and JS files only loaded when needed
- Optimized database queries
- Proper caching where applicable

## [1.0.1] - 2024-12-19

### Fixed
- Fixed "Call to undefined function lbn_check_requirements()" error during plugin activation
- Corrected file inclusion order in main plugin file to ensure helper functions are loaded before being called
- Fixed empty admin page issue by improving settings registration and adding fallback form
- Enhanced admin page reliability with better error handling and debug information

## [Unreleased]

### Planned Features
- Multiple notice types (info, warning, error, success)
- Notice templates
- User role-based notice display
- Email notifications for scheduled notices
- Import/export settings functionality
- Advanced styling options
- Custom CSS editor
- Notice analytics and tracking