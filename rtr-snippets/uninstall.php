<?php
/**
 * Uninstall RTR Custom Styles and Scripts
 *
 * @package RTR_Custom_Styles_Scripts
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options from the database.
delete_option( 'rtr_css_custom_styles' );
delete_option( 'rtr_css_custom_scripts' );
delete_option( 'rtr_css_header_scripts' );
delete_option( 'rtr_css_footer_scripts' );