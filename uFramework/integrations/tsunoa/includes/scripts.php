<?php
/**
 * Scripts
 *
 * @package     uFramework\Tsunoa\Scripts
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'tsunoa_register_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'tsunoa_register_scripts' );
    function tsunoa_register_scripts() {
        // Use minified libraries if SCRIPT_DEBUG is turned off
        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        // Stylesheets
        wp_register_style( 'tsunoa-css', TSUNOA_URL . 'assets/css/tsunoa' . $suffix . '.css', array( ), '1.0.0', 'all' );

        // Scripts
        wp_register_script( 'tsunoa-js', TSUNOA_URL . 'assets/js/tsunoa' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
    }
}

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
if( ! function_exists( 'tsunoa_admin_enqueue_scripts' ) ) {
    add_action( 'admin_enqueue_scripts', 'tsunoa_admin_enqueue_scripts', 100 );
    function tsunoa_admin_enqueue_scripts( $hook ) {
        //Stylesheets
        wp_enqueue_style( 'tsunoa-css' );

        // Localize scripts
        $script_parameters = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'none'     => wp_create_nonce( 'tsunoa_nonce' ),
        );

        wp_localize_script( 'tsunoa-js', 'tsunoa', $script_parameters );

        //Scripts
        wp_enqueue_script( 'tsunoa-js' );
    }
}