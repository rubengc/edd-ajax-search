<?php
/**
 * Scripts
 *
 * @package     uFramework\Easy_Digital_Downloads\Scripts
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'uframework_edd_register_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'uframework_edd_register_scripts' );
    function uframework_edd_register_scripts() {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        $uri = plugin_dir_url( __FILE__ );

        // Stylesheets
        wp_register_style( 'uframework-edd-css', $uri . '../assets/css/uframework-edd' . $suffix . '.css', array( ), '1.0.0', 'all' );

        // Scripts
        wp_register_script( 'uframework-edd-js', $uri . '../assets/js/uframework-edd' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
    }
}

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'uframework_edd_admin_enqueue_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'uframework_edd_admin_enqueue_scripts', 100 );
    function uframework_edd_admin_enqueue_scripts( $hook ) {
        //Stylesheets
        wp_enqueue_style( 'uframework-edd-css' );

        //Scripts
        wp_enqueue_script( 'uframework-edd-js' );
    }
}