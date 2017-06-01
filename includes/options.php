<?php
/**
 * Settings
 *
 * @package     EDD\Ajax_Search\Settings
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Ajax_Search_Settings' ) ) {

    class EDD_Ajax_Search_Options extends uFramework_Options {

        public function __construct() {
            $this->options_key = 'edd-ajax-search';

            add_filter( 'tsunoa_' . $this->options_key . '_settings', array( $this, 'register_settings_url' ) );

            parent::__construct();
        }

        public function register_settings_url( $url ) {
            return 'admin.php?page=' . $this->options_key;
        }

        /**
         * Add the options metabox to the array of metaboxes
         * @since  0.1.0
         */
        public function register_form() {
            // Options page configuration
            $args = array(
                'key'      => $this->options_key,
                'title'    => __( 'EDD Ajax Search', 'edd-ajax-search' ),
                'topmenu'  => 'tsunoa',
                'cols'     => 2,
                'boxes'    => $this->boxes(),
                'tabs'     => $this->tabs(),
                'menuargs' => array(
                    'menu_title' => __( 'EDD Ajax Search', 'edd-ajax-search' ),
                ),
                'savetxt'  => __( 'Save changes' ),
                'admincss' => '.' . $this->options_key . ' #side-sortables{padding-top: 0 !important;}' .
                      '.' . $this->options_key . '.cmo-options-page .columns-2 #postbox-container-1{margin-top: 0 !important;}' .
                      '.' . $this->options_key . '.cmo-options-page .nav-tab-wrapper{display: none;}'
            );

            // Create the options page
            new Cmb2_Metatabs_Options( $args );
        }

        /**
         * Setup form in settings page
         *
         * @return array
         */
        public function boxes() {
            // Holds all CMB2 box objects
            $boxes = array();

            // Default options to all boxes
            $show_on = array(
                'key'   => 'options-page',
                'value' => array( $this->options_key ),
            );

            // Search input box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-search-input',
                'title'   => __( 'Search input', 'edd-ajax-search' ),
                'show_on' => $show_on,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Placeholder', 'edd-ajax-search' ),
                'desc' => __( 'Text to show when search input is empty', 'edd-ajax-search' ),
                'id'   => 'search_placeholder',
                'type' => 'text',
                'default' => __( 'Type to start search...', 'edd-ajax-search' ),
            ) );

            $cmb->add_field( array(
                'name' => __( 'Minimum number of characters', 'edd-ajax-search' ),
                'desc' => __( 'Minimum number of characters to execute the search', 'edd-ajax-search' ),
                'id'   => 'minimum_characters',
                'type' => 'text_small',
                'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
                'default' => 3,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Maximum number of results', 'edd-ajax-search' ),
                'desc' => __( 'Maximum number of results search will return', 'edd-ajax-search' ),
                'id'   => 'maximum_results',
                'type' => 'text_small',
                'attributes' => array(
                    'type' => 'number',
                    'pattern' => '\d*',
                ),
                'default' => 5,
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Submit button box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-submit-button',
                'title'   => __( 'Submit button', 'edd-ajax-search' ),
                'show_on' => $show_on,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Hide submit button', 'edd-ajax-search' ),
                'desc' => __( 'Hide submit button by default (overwritable by hide_submit="no")', 'edd-ajax-search' ),
                'id'   => 'submit_hide',
                'type' => 'checkbox',
            ) );

            $cmb->add_field( array(
                'name' => __( 'Label', 'edd-ajax-search' ),
                'desc' => __( 'Text to show in submit button', 'edd-ajax-search' ),
                'id'   => 'submit_label',
                'type' => 'text',
                'default' => __( 'Search', 'edd-ajax-search' ),
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Category input box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-categories-input',
                'title'   => __( 'Categories filter', 'edd-ajax-search' ),
                'show_on' => $show_on,
            ) );

            $cmb->add_field( array(
                'name' => __( 'Hide categories filter', 'edd-ajax-search' ),
                'desc' => __( 'Hide categories filter by default (overwritable by hide_categories="no")', 'edd-ajax-search' ),
                'id'   => 'categories_hide',
                'type' => 'checkbox',
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Submit box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-submit',
                'title'   => __( 'Save changes', 'edd-ajax-search' ),
                'show_on' => $show_on,
                'context' => 'side',
            ) );

            $cmb->add_field( array(
                'name' => '',
                'desc' => '',
                'id'   => 'submit_box',
                'type' => 'title',
                'render_row_cb' => array( $this, 'submit_box' )
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            // Shortcode box
            $cmb = new_cmb2_box( array(
                'id'      => $this->options_key . '-shortcode',
                'title'   => __( 'Shortcode generator', 'edd-ajax-search' ),
                'show_on' => $show_on,
                'context' => 'side',
            ) );

            $cmb->add_field( array(
                'name' => '',
                'desc' =>  __( 'From this options page you can configure default parameters for [edd_ajax_search] shortcode. Also using form bellow you can generate a shortcode to place it in any page.', 'edd-ajax-search' ),
                'id'   => 'shortcode_generator',
                'type' => 'title',
                'after' => array( $this, 'shortcode_generator' ),
            ) );

            $cmb->object_type( 'options-page' );

            $boxes[] = $cmb;

            return $boxes;
        }


        /**
         * Settings page tabs
         *
         * @return array
         */
        public function tabs() {
            $tabs = array();

            $tabs[] = array(
                'id'    => 'general',
                'title' => 'General',
                'desc'  => '',
                'boxes' => array(
                    $this->options_key . '-search-input',
                    $this->options_key . '-submit-button',
                    $this->options_key . '-categories-input',
                ),
            );

            return $tabs;
        }

        /**
         * Submit box
         *
         * @param array      $field_args
         * @param CMB2_Field $field
         */
        public function submit_box( $field_args, $field ) {
            ?>
            <p>
                <a href="<?php echo tsunoa_product_docs_url( $this->options_key ); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-media-text"></i> <?php _e( 'Documentation' ); ?></a>
                <a href="<?php echo tsunoa_product_url( $this->options_key ); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-cart"></i> <?php _e( 'Get support and pro features', 'edd-ajax-search' ); ?></a>
            </p>
            <div class="cmb2-actions">
                <input type="submit" name="submit-cmb" value="<?php _e( 'Save changes' ); ?>" class="button-primary">
            </div>
            <?php
        }

        /**
         * Shortcode generator
         *
         * @param array      $field_args
         * @param CMB2_Field $field
         */
        public function shortcode_generator( $field_args, $field ) {
            ?>
            <div id="edd-ajax-search-shortcode-form" class="uframework-shortcode-generator">
                <p>
                    <textarea type="text" id="edd-ajax-search-shortcode-input" data-shortcode="edd_ajax_search" readonly="readonly">[edd_ajax_search form_order="search,categories,post-types,submit" hide_submit="no" hide_categories="no" hide_post_types="no"]</textarea>
                </p>

                <p>
                    <input type="checkbox" id="shortcode_hide_submit" data-shortcode-attr="hide_submit">
                    <label for="shortcode_hide_submit"><?php _e( 'Hide submit button', 'edd-ajax-search' ); ?></label>
                </p>

                <p>
                    <input type="checkbox" id="shortcode_hide_categories" data-shortcode-attr="hide_categories">
                    <label for="shortcode_hide_categories"><?php _e( 'Hide categories', 'edd-ajax-search' ); ?></label>
                </p>
            </div>
            <?php
        }

    }

}// End if class_exists check