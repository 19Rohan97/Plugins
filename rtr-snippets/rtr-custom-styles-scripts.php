<?php

/**
 * Plugin Name: RTR Custom Styles and Scripts
 * Plugin URI: https://example.com/rtr-custom-styles-scripts
 * Description: A plugin that allows users to add custom styles and scripts to their WordPress site.
 * Version: 1.0.0
 * Author: RTR
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: rtr-custom-styles-scripts
 * Domain Path: /languages
 *
 * @package RTR_Custom_Styles_Scripts
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

// Define plugin constants
define('RTR_CSS_VERSION', '1.0.0');
define('RTR_CSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RTR_CSS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RTR_CSS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_rtr_custom_styles_scripts()
{
    // Initialize default options if they don't exist
    if (! get_option('rtr_css_custom_styles')) {
        add_option('rtr_css_custom_styles', '');
    }
    if (! get_option('rtr_css_custom_scripts')) {
        add_option('rtr_css_custom_scripts', '');
    }
    if (! get_option('rtr_css_header_scripts')) {
        add_option('rtr_css_header_scripts', '');
    }
    if (! get_option('rtr_css_footer_scripts')) {
        add_option('rtr_css_footer_scripts', '');
    }
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_rtr_custom_styles_scripts()
{
    // Nothing to do here for now
}

register_activation_hook(__FILE__, 'activate_rtr_custom_styles_scripts');
register_deactivation_hook(__FILE__, 'deactivate_rtr_custom_styles_scripts');

/**
 * Admin menu and settings page
 */
function rtr_css_admin_menu()
{
    add_menu_page(
        __('RTR Custom Styles & Scripts', 'rtr-custom-styles-scripts'),
        __('RTR Styles & Scripts', 'rtr-custom-styles-scripts'),
        'manage_options',
        'rtr-custom-styles-scripts',
        'rtr_css_admin_page',
        'dashicons-editor-code',
        80
    );
}
add_action('admin_menu', 'rtr_css_admin_menu');

/**
 * Admin page callback
 */
function rtr_css_admin_page()
{
    // Check user capabilities
    if (! current_user_can('manage_options')) {
        return;
    }

    // Save settings if form is submitted
    if (isset($_POST['rtr_css_save_settings']) && check_admin_referer('rtr_css_settings_nonce')) {
        // Sanitize and save custom CSS
        if (isset($_POST['rtr_css_custom_styles'])) {
            $custom_css = wp_strip_all_tags($_POST['rtr_css_custom_styles']);
            update_option('rtr_css_custom_styles', $custom_css);
        }

        // Sanitize and save custom scripts
        if (isset($_POST['rtr_css_custom_scripts'])) {
            $custom_js = $_POST['rtr_css_custom_scripts'];
            update_option('rtr_css_custom_scripts', $custom_js);
        }

        // Sanitize and save header scripts
        if (isset($_POST['rtr_css_header_scripts'])) {
            $header_scripts = $_POST['rtr_css_header_scripts'];
            update_option('rtr_css_header_scripts', $header_scripts);
        }

        // Sanitize and save footer scripts
        if (isset($_POST['rtr_css_footer_scripts'])) {
            $footer_scripts = $_POST['rtr_css_footer_scripts'];
            update_option('rtr_css_footer_scripts', $footer_scripts);
        }

        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully!', 'rtr-custom-styles-scripts') . '</p></div>';
    }

    // Get current settings
    $custom_css = get_option('rtr_css_custom_styles', '');
    $custom_js = get_option('rtr_css_custom_scripts', '');
    $header_scripts = get_option('rtr_css_header_scripts', '');
    $footer_scripts = get_option('rtr_css_footer_scripts', '');

    // Display the settings form
?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field('rtr_css_settings_nonce'); ?>

        <div class="rtr-css-tabs">
            <div class="nav-tab-wrapper">
                <a href="#tab-css"
                    class="nav-tab nav-tab-active"><?php esc_html_e('Custom CSS', 'rtr-custom-styles-scripts'); ?></a>
                <a href="#tab-js"
                    class="nav-tab"><?php esc_html_e('Custom JavaScript', 'rtr-custom-styles-scripts'); ?></a>
                <a href="#tab-header"
                    class="nav-tab"><?php esc_html_e('Header Scripts', 'rtr-custom-styles-scripts'); ?></a>
                <a href="#tab-footer"
                    class="nav-tab"><?php esc_html_e('Footer Scripts', 'rtr-custom-styles-scripts'); ?></a>
            </div>

            <div id="tab-css" class="tab-content" style="display: block;">
                <h2><?php esc_html_e('Custom CSS', 'rtr-custom-styles-scripts'); ?></h2>
                <p><?php esc_html_e('Add custom CSS styles to your site. These styles will be applied to the front end of your site.', 'rtr-custom-styles-scripts'); ?>
                </p>
                <textarea name="rtr_css_custom_styles" id="rtr_css_custom_styles" rows="15"
                    class="large-text code"><?php echo esc_textarea($custom_css); ?></textarea>
            </div>

            <div id="tab-js" class="tab-content" style="display: none;">
                <h2><?php esc_html_e('Custom JavaScript', 'rtr-custom-styles-scripts'); ?></h2>
                <p><?php esc_html_e('Add custom JavaScript to your site. This code will be added to the footer of your site.', 'rtr-custom-styles-scripts'); ?>
                </p>
                <textarea name="rtr_css_custom_scripts" id="rtr_css_custom_scripts" rows="15"
                    class="large-text code"><?php echo esc_textarea($custom_js); ?></textarea>
            </div>

            <div id="tab-header" class="tab-content" style="display: none;">
                <h2><?php esc_html_e('Header Scripts', 'rtr-custom-styles-scripts'); ?></h2>
                <p><?php esc_html_e('Add custom scripts to the head section of your site. Useful for verification tags, analytics, etc.', 'rtr-custom-styles-scripts'); ?>
                </p>
                <textarea name="rtr_css_header_scripts" id="rtr_css_header_scripts" rows="15"
                    class="large-text code"><?php echo esc_textarea($header_scripts); ?></textarea>
            </div>

            <div id="tab-footer" class="tab-content" style="display: none;">
                <h2><?php esc_html_e('Footer Scripts', 'rtr-custom-styles-scripts'); ?></h2>
                <p><?php esc_html_e('Add custom scripts to the footer of your site. This is the recommended location for most scripts.', 'rtr-custom-styles-scripts'); ?>
                </p>
                <textarea name="rtr_css_footer_scripts" id="rtr_css_footer_scripts" rows="15"
                    class="large-text code"><?php echo esc_textarea($footer_scripts); ?></textarea>
            </div>
        </div>

        <p class="submit">
            <input type="submit" name="rtr_css_save_settings" class="button button-primary"
                value="<?php esc_attr_e('Save Settings', 'rtr-custom-styles-scripts'); ?>">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab functionality
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();

        // Hide all tab contents
        $('.tab-content').hide();

        // Remove active class from all tabs
        $('.nav-tab').removeClass('nav-tab-active');

        // Add active class to current tab
        $(this).addClass('nav-tab-active');

        // Show the selected tab content
        $($(this).attr('href')).show();
    });
});
</script>
<?php
}

/**
 * Enqueue admin scripts and styles
 */
function rtr_css_admin_enqueue_scripts($hook)
{
    if ('toplevel_page_rtr-custom-styles-scripts' !== $hook) {
        return;
    }

    wp_enqueue_style('rtr-css-admin-styles', RTR_CSS_PLUGIN_URL . 'admin/css/admin.css', array(), RTR_CSS_VERSION);
    wp_enqueue_script('jquery');
}
add_action('admin_enqueue_scripts', 'rtr_css_admin_enqueue_scripts');

/**
 * Format CSS code for better readability
 */
function rtr_css_format_css($css)
{
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    
    // Add line break after each rule
    $css = preg_replace('/}\s*/', "}\n", $css);
    
    // Add line break after each selector
    $css = preg_replace('/{\s*/', "{\n    ", $css);
    
    // Add indentation to properties
    $css = preg_replace('/;\s*/', ";\n    ", $css);
    
    // Clean up extra spaces
    $css = preg_replace('/\s+{\s+/', " {\n    ", $css);
    
    // Remove extra spaces at the end of lines
    $css = preg_replace('/\s+}/', "\n}", $css);
    
    // Remove trailing semicolon in last property
    $css = preg_replace('/;\s*}/', "\n}", $css);
    
    return trim($css);
}

/**
 * Add custom CSS to the front end
 */
function rtr_css_add_custom_css()
{
    $custom_css = get_option('rtr_css_custom_styles', '');

    if (! empty($custom_css)) {
        // Format the CSS for better readability
        $formatted_css = rtr_css_format_css($custom_css);
        
        echo '<style type="text/css" id="rtr-custom-css">' . wp_strip_all_tags($formatted_css) . '</style>';
    }
}
add_action('wp_head', 'rtr_css_add_custom_css', 999);

/**
 * Format JavaScript code for better readability
 */
function rtr_css_format_js($js)
{
    // Remove comments (both // and /* */)
    $js = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js);
    $js = preg_replace('!//.*!', '', $js);
    
    // Remove whitespace
    $js = preg_replace('/\s+/', ' ', $js);
    
    // Add line break after semicolons
    $js = preg_replace('/;\s*/', ";\n", $js);
    
    // Add line break after opening braces
    $js = preg_replace('/{\s*/', "{\n    ", $js);
    
    // Add line break before closing braces
    $js = preg_replace('/\s*}/', "\n}", $js);
    
    // Add indentation to nested blocks
    $lines = explode("\n", $js);
    $indentLevel = 0;
    $formattedJs = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line)) {
            continue;
        }
        
        // Decrease indent level for lines with closing braces
        if (substr($line, 0, 1) === '}') {
            $indentLevel--;
        }
        
        // Add appropriate indentation
        if ($indentLevel > 0) {
            $formattedJs .= str_repeat('    ', $indentLevel) . $line . "\n";
        } else {
            $formattedJs .= $line . "\n";
        }
        
        // Increase indent level for lines with opening braces
        if (substr($line, -1) === '{') {
            $indentLevel++;
        }
    }
    
    return trim($formattedJs);
}

/**
 * Add custom JavaScript to the front end
 */
function rtr_css_add_custom_js()
{
    $custom_js = get_option('rtr_css_custom_scripts', '');

    if (! empty($custom_js)) {
        // Format the JavaScript for better readability
        $formatted_js = rtr_css_format_js($custom_js);
        
        echo '<script type="text/javascript" id="rtr-custom-js">' . $formatted_js . '</script>';
    }
}
add_action('wp_footer', 'rtr_css_add_custom_js', 999);

/**
 * Add header scripts
 */
function rtr_css_add_header_scripts()
{
    $header_scripts = get_option('rtr_css_header_scripts', '');

    if (! empty($header_scripts)) {
        echo $header_scripts;
    }
}
add_action('wp_head', 'rtr_css_add_header_scripts', 999);

/**
 * Add footer scripts
 */
function rtr_css_add_footer_scripts()
{
    $footer_scripts = get_option('rtr_css_footer_scripts', '');

    if (! empty($footer_scripts)) {
        echo $footer_scripts;
    }
}
add_action('wp_footer', 'rtr_css_add_footer_scripts', 999);