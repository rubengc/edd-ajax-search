<?php
/**
 * Tsunoa
 *
 * @package     uFramework\Tsunoa
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'TSUNOA_LOADED' ) ) {
    define( 'TSUNOA_VER', '1.0.0' );
    define( 'TSUNOA_DIR', __DIR__ );
    define( 'TSUNOA_URL', plugin_dir_url( __DIR__ ) . 'tsunoa/' );

    // Classes
    require_once __DIR__ . '/classes/class-license.php';

    // Includes
    require_once __DIR__ . '/includes/admin.php';
    require_once __DIR__ . '/includes/functions.php';
    require_once __DIR__ . '/includes/scripts.php';

    define('TSUNOA_LOADED', true);
}

add_action( 'admin_init', function() {
    tsunoa_license_plugin( __DIR__ . '/../../../' );
});