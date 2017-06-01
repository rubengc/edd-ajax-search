<?php
/**
 * Shortcodes
 *
 * @package     EDD\Ajax_Search\Shortcodes
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Ajax_Search_Shortcodes' ) ) {

    class EDD_Ajax_Search_Shortcodes {

        public function __construct() {
            // [edd_ajax_search]
            add_shortcode( 'edd_ajax_search', array( $this, 'ajax_search' ) );
        }

        public function ajax_search( $atts, $content = null ) {
            $atts = shortcode_atts( array(
                'hide_categories'  => 'no',
                'hide_submit'      => 'no',
            ), $atts, 'edd_ajax_search' );


            ob_start();

            do_action( 'edd_ajax_search_form_before' );

            ?>

            <div class="edd-ajax-search-container">

                <form role="search" method="get" id="edd-ajax-search-form" action="<?php echo esc_url( home_url( '/' ) ) ?>">

                    <?php do_action( 'edd_ajax_search_fields_top' ); ?>

                    <input type="search"
                           value="<?php echo get_search_query() ?>"
                           name="s"
                           id="edd-ajax-search-search"
                           class="edd-ajax-search-search edd-ajax-search-input"
                           placeholder="<?php echo edd_ajax_search()->options->get( 'search_placeholder', __ ( 'Type to start search...', 'edd-ajax-search' ) ); ?>" />

                    <?php if( $atts[ 'hide_submit' ] == 'no' ) :
                        echo wp_dropdown_categories( array(
                            'hierarchical'     => 1,
                            'orderby'          => 'name',
                            'order'            => 'asc',
                            'depth'            => 0,
                            'hide_empty'       => 0,
                            'show_count'       => 0,
                            'name'             => 'cat',
                            'id'               => 'edd-ajax-search-categories',
                            'taxonomy'         => 'download_category',
                            'echo'             => 0,
                            'title_li'         => '',
                            'class'            => 'edd-ajax-search-categories edd-ajax-search-select',
                            'include'          => array(),
                            'show_option_all'  => __( 'All', 'edd-ajax-search' )
                        ) );
                    endif; ?>

                    <?php if( $atts[ 'hide_submit' ] == 'no' ) : ?>
                        <input type="submit"
                               id="edd-ajax-search-submit"
                               class="edd-ajax-search-submit edd-ajax-search-button"
                               value="<?php echo edd_ajax_search()->options->get( 'submit_label', __ ( 'Search', 'edd-ajax-search' ) ); ?>" />
                    <?php endif; ?>


                    <?php do_action( 'edd_ajax_search_fields_bottom' ); ?>

                </form>

            </div>

            <?php

            do_action( 'edd_ajax_search_form_after' );

            $content = ob_get_clean();

            return $content;
        }

    }

}// End if class_exists check