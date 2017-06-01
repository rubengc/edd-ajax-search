<?php
/**
 * Easy Digital Downloads
 *
 * @package     uFramework\Easy_Digital_Downloads
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'UFRAMEWORK_EDD_LOADED' ) ) {

    // Includes
    require_once __DIR__ . '/includes/functions.php';
    require_once __DIR__ . '/includes/scripts.php';

    define('UFRAMEWORK_EDD_LOADED', true);
}