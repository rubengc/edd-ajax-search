<?php
/**
 * Plugin Name:     EDD Ajax Search
 * Plugin URI:      https://wordpress.org/plugins/edd-ajax-search
 * Description:     Live product search for Easy Digital Downloads.
 * Version:         1.0.0
 * Author:          Tsunoa
 * Author URI:      https://tsunoa.com
 * Text Domain:     edd-ajax-search
 *
 * @package         EDD\Ajax_Search
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Ajax_Search' ) ) {

    /**
     * Main EDD_Ajax_Search class
     *
     * @since       1.0.0
     */
    class EDD_Ajax_Search {

        /**
         * @var         EDD_Ajax_Search $instance The one true EDD_Ajax_Search
         * @since       1.0.0
         */
        private static $instance;

        /**
         * @var         EDD_Ajax_Search_Functions EDD Ajax Search functions
         * @since       1.0.0
         */
        public $functions;

        /**
         * @var         EDD_Ajax_Search_Options EDD Ajax Search options
         * @since       1.0.0
         */
        public $options;

        /**
         * @var         EDD_Ajax_Search_Scripts EDD Ajax Search scripts
         * @since       1.0.0
         */
        public $scripts;

        /**
         * @var         EDD_Ajax_Search_Shortcodes EDD Ajax Search shortcodes
         * @since       1.0.0
         */
        public $shortcodes;

        /**
         * @var         EDD_Ajax_Search_Widgets EDD Ajax Search widgets
         * @since       1.0.0
         */
        public $widgets;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true EDD_Ajax_Search
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new EDD_Ajax_Search();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }

        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'EDD_AJAX_SEARCH_VER', '1.0.0' );

            // Plugin path
            define( 'EDD_AJAX_SEARCH_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'EDD_AJAX_SEARCH_URL', plugin_dir_url( __FILE__ ) );
        }

        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once EDD_AJAX_SEARCH_DIR . 'uFramework/uFramework.php';

            // Include scripts
            require_once EDD_AJAX_SEARCH_DIR . 'includes/functions.php';
            require_once EDD_AJAX_SEARCH_DIR . 'includes/options.php';
            require_once EDD_AJAX_SEARCH_DIR . 'includes/scripts.php';
            require_once EDD_AJAX_SEARCH_DIR . 'includes/shortcodes.php';
            require_once EDD_AJAX_SEARCH_DIR . 'includes/widgets.php';


            $this->functions = new EDD_Ajax_Search_Functions();
            $this->options = new EDD_Ajax_Search_Options();
            $this->scripts = new EDD_Ajax_Search_Scripts();
            $this->shortcodes = new EDD_Ajax_Search_Shortcodes();
            $this->widgets = new EDD_Ajax_Search_Widgets();
        }

        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = EDD_AJAX_SEARCH_DIR . '/languages/';
            $lang_dir = apply_filters( 'edd_ajax_search_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'edd-ajax-search' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'edd-ajax-search', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/edd-ajax-search/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/edd-ajax-search/ folder
                load_textdomain( 'edd-ajax-search', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/edd-ajax-search/languages/ folder
                load_textdomain( 'edd-ajax-search', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'edd-ajax-search', false, $lang_dir );
            }
        }

        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            add_filter( 'tsunoa_edd-ajax-search_has_premium_version', '__return_true' );
        }
    }
}


/**
 * The main function responsible for returning the one true EDD_Ajax_Search instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_Ajax_Search The one true EDD_Ajax_Search
 */
function edd_ajax_search() {
    return EDD_Ajax_Search::instance();
}
add_action( 'plugins_loaded', 'edd_ajax_search' );