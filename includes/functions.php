<?php
/**
 * Functions
 *
 * @package     EDD\Ajax_Search\Functions
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'EDD_Ajax_Search_Functions' ) ) {

    class EDD_Ajax_Search_Functions {

        public function __construct() {
            // Ajax requests
            add_action( 'wp_ajax_edd_ajax_search', array( $this, 'ajax_search' ) );
            add_action( 'wp_ajax_nopriv_edd_ajax_search', array( $this, 'ajax_search' ) );
        }

        /**
         * Executes the search query to match best results
         */
        public function ajax_search() {
            $search = $_REQUEST['s'];

            $transient_name = 'edd_ajax_search_' . $search;

            // If this search has been cached already, then use cached result
            if ( false === ( $results = get_transient( $transient_name ) ) ) {

                $limit = edd_ajax_search()->options->get( 'maximum_results', 5 );

                $query_args = array(
                    'post_status' => 'publish',
                    'post_type' => 'download',
                    's' => $search,
                    'posts_per_page' => $limit,
                );

                // Categories check
                if( ( isset( $_REQUEST['cat'] ) && intval( $_REQUEST['cat'] ) > 0 ) ) {
                    $query_args['tax_query'] = array(
                        'relation' => 'OR'
                    );

                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'download_category',
                        'field' => 'term_id',
                        'terms' => $_REQUEST['cat'],
                    );
                }

                $query = new WP_Query( $query_args );

                if ( $query->have_posts() ) {
                    while ($query->have_posts()) {
                        $query->the_post();

                        ob_start(); ?>

                        <div class="edd-ajax-search-result edd-ajax-search-result-download">
                            <div class="edd_download_image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo get_the_post_thumbnail( get_the_ID(), array( 80, 80 ), array( 'title' => esc_attr( get_the_title() ) ) ); ?>
                                </a>
                            </div>

                            <div class="edd_download_title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </div>

                            <div class="edd_price edd_download_price">
                                <?php edd_price( get_the_ID() ); ?>
                            </div>

                            <?php if ( has_excerpt() ) : ?>
                                <div class="edd_download_excerpt">
                                    <?php echo wp_trim_words( get_post_field( 'post_excerpt', get_the_ID() ), 30 ); ?>
                                </div>
                            <?php elseif ( get_the_content() ) : ?>
                                <div class="edd_download_excerpt">
                                    <?php echo wp_trim_words( get_post_field( 'post_content', get_the_ID() ), 30 ); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php $result_html = ob_get_clean();

                        $results[] = array(
                            'value' => get_the_title(),
                            'html' => $result_html,
                            'link' => get_the_permalink(),
                        );
                    }

                    $view_more_url_args = array(
                        's' => $search,
                        'post_type' => 'download',
                    );

                    if ( isset( $_REQUEST['cat'] ) && intval( $_REQUEST['cat'] ) > 0 ) {
                        $view_more_url_args['cat'] = $_REQUEST['cat'];
                    }

                    $view_more_url = add_query_arg( $view_more_url_args, home_url( '/' ) );

                    $results[] = array(
                        'value' => $search,
                        'html' => '<a href="' . $view_more_url . '" class="edd-ajax-search-more">' . __( 'View more', 'edd-search-ajax' ) . '</a>',
                        'link' => $view_more_url,
                    );
                } else {
                    $results[] = array(
                        'value' => $search,
                        'html' => '<div class="edd-ajax-search-no-results">' . __( 'No results found', 'edd-search-ajax' ) . '</div>',
                    );
                }

                // Set a transient of 12 hours to the current search
                set_transient( $transient_name, $results, 12 * HOUR_IN_SECONDS );

            }

            wp_send_json( $results );
            wp_die();
        }

    }

}