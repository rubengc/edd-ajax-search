<?php
/**
 * Funtions
 *
 * @package     uFramework\Tsunoa\Funtions
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( ! function_exists( 'tsunoa_license_plugin' ) ) {
    function tsunoa_license_plugin( $plugin_path ) {
        if( is_admin() && function_exists( 'get_plugin_data' ) ) {
            $file = uframework_scandir( $plugin_path, 'php' );

            if( is_array( $file ) ) {
                $filename = array_keys( $file )[0];

                $plugin_data = get_plugin_data( $file[$filename], false );

                if ( strpos( $plugin_data["PluginURI"], 'wordpress.org' ) === FALSE ) {
                    new Tsunoa_License(
                        $plugin_path,
                        $plugin_data['Title'],
                        $plugin_data['Version']
                    );
                }
            }
        }
    }
}

if( ! function_exists( 'tsunoa_url' ) ) {
    function tsunoa_url() {
        return 'https://tsunoa.com';
    }
}

if( ! function_exists( 'tsunoa_support_url' ) ) {
    function tsunoa_support_url() {
        return tsunoa_url() . '/support';
    }
}

if( ! function_exists( 'tsunoa_product_url' ) ) {
    function tsunoa_product_url( $product_slug ) {
        return tsunoa_url() . '/downloads/' . $product_slug;
    }
}

if( ! function_exists( 'tsunoa_product_docs_url' ) ) {
    function tsunoa_product_docs_url( $product_slug ) {
        return tsunoa_url() . '/docs/' . $product_slug;
    }
}