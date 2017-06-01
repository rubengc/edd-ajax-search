<?php
/**
 * Widgets
 *
 * @package     EDD\Ajax_Search\Widgets
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Ajax_Search_Widgets' ) ) {

    class EDD_Ajax_Search_Widgets {

        public function __construct() {
            require_once EDD_AJAX_SEARCH_DIR . 'widgets/edd-ajax-search-widget.php';

            add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        }

        public function widgets_init() {
            register_widget( 'EDD_Ajax_Search_Widget' );
        }

    }

}