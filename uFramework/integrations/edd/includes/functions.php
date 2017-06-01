<?php
/**
 * Functions
 *
 * @package     uFramework\Easy_Digital_Downloads\Functions
 * @since       1.0.0
 *
 * @author          Tsunoa
 * @copyright       Copyright (c) Tsunoa
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( ! function_exists( 'uframework_edd_download_template_form' ) ) {
    function uframework_edd_download_template_form( $options_key, $options_prefix = '' ) {
        $thumbnail_size = cmb2_get_option( $options_key, $options_prefix . 'download_thumbnail_size', 80 );

        // Download placeholder to output html in a sortable form
        $download_placeholder = uframework_edd_download_placeholder( $thumbnail_size );

        ob_start();
        ?>
        <ul class="uframework-sortable download-parts">
            <?php

            foreach( cmb2_get_option( $options_key, $options_prefix . 'download_parts_order', array( 'thumbnail', 'title', 'author', 'categories', 'excerpt', 'price', 'tags', 'purchase' ) ) as $download_part ) :
                ?>
                <li>
                    <span class="uframework-sortable-handle">
                        <i class="dashicons-before dashicons-move"></i>
                    </span>
                    <input type="checkbox" name="download_<?php echo $download_part; ?>[]" value="on" <?php echo checked( (bool) cmb2_get_option( $options_key, $options_prefix . 'download_' . $download_part, false ), true ); ?> class="uframework-toggle-visibility">
                    <input type="hidden" name="download_parts_order[]" value="<?php echo $download_part; ?>">
                    <?php echo $download_placeholder[$download_part]; ?>
                </li>
                <?php
            endforeach;

            ?>
        </ul>

        <p class="cmb2-metabox-description"><?php _e( 'Drag and drop to setup how downloads will be displayed', 'uframework' ); ?></p>
        <?php
        return ob_get_clean();
    }
}

if( ! function_exists( 'uframework_edd_save_download_template_form' ) ) {
    function uframework_edd_save_download_template_form( $uframework_options ) {
        // Download parts order
        if( isset( $_REQUEST['download_parts_order'] ) && ! empty( $_REQUEST['download_parts_order'] ) ) {
            $uframework_options->update( 'download_parts_order', $_REQUEST['download_parts_order'], true );
        }

        // Download parts visibility
        $checkboxes_options = array(
            'download_thumbnail',
            'download_title',
            'download_author',
            'download_categories',
            'download_excerpt',
            'download_price',
            'download_tags',
            'download_purchase',
        );

        foreach( $checkboxes_options as $checkbox_option ) {
            $uframework_options->update( $checkbox_option, isset( $_REQUEST[$checkbox_option] ) );
        }
    }
    add_action( 'uframework_save_options', 'uframework_edd_save_download_template_form' );
}


if( ! function_exists( 'uframework_edd_download_placeholder' ) ) {
    function uframework_edd_download_placeholder( $thumbnail_size ) {
        return array(
            'thumbnail' => '<div class="download-part-thumbnail" style="width: ' . $thumbnail_size . 'px; height: ' . $thumbnail_size . 'px;"><span style="line-height: ' . $thumbnail_size . 'px;">' . $thumbnail_size . 'x' . $thumbnail_size . '</span></div>',
            'title' => '<div class="download-part-title"><h3>Download Title</h3></div>',
            'author' => '<div class="download-part-author"><a href="#">Author</a></div>',
            'categories' => '<div class="download-part-categories"><a href="#">Category 1</a>, <a href="#">Category 2</a></div>',
            'price' => '<div class="download-part-price"><span>100.00$</span></div>',
            'excerpt' => '<div class="download-part-excerpt"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi...</span></div>',
            'tags' => '<div class="download-part-tags"><a href="#">Tag 1</a> <a href="#">Tag 2</a></div>',
            'purchase' => '<div class="download-part-purchase"><a href="#">Purchase</a></div>',
        );
    }
}

if( ! function_exists( 'uframework_edd_download_template_tags_description' ) ) {
    function uframework_edd_download_template_tags_description() {
        return
            '{download_title} - Download\'s title' . '<br>' .
            '{download_url} - URL to the download' . '<br>' .
            '{download_link} - Link to the download (shortcut of &lt;a href="{download_url}"&gt;{download_title}&lt;/a&gt;)' . '<br>' .
            '{download_categories} - Download\'s categories list' . '<br>' .
            '{download_tags} - Download\'s tags list' . '<br>' .
            '{download_price} - Download\'s price' . '<br>' .
            '{download_excerpt} - Download\'s excerpt' . '<br>' .
            '{download_purchase} - Download\'s purchase button'
        ;
    }
}

if( ! function_exists( 'uframework_edd_parse_download_template_tags' ) ) {
    function uframework_edd_parse_download_template_tags( $content, $download_id = null ) {
        if( $download_id == null ) {
            $download_id = get_the_ID();
        }

        $download = get_post( $download_id );

        if( $download ) {

            $excerpt_length = 30;
            $excerpt = ( has_excerpt( $download_id ) ) ? wp_trim_words( get_post_field( 'post_excerpt', $download_id ), $excerpt_length ) :  wp_trim_words( get_post_field( 'post_content', $download_id ), $excerpt_length );

            $download_categories = get_the_term_list( $download_id, 'download_category', '', ', ', '' );

            $download_tags = get_the_term_list( $download_id, 'download_tag', '', ', ', '' );

            $template_tags = array(
                '{download_title}',
                '{download_url}',
                '{download_link}',
                '{download_categories}',
                '{download_tags}',
                '{download_price}',
                '{download_excerpt}',
                '{download_purchase}'
            );

            $replacement = array(
                get_the_title( $download ),
                get_the_permalink( $download ),
                '<a href="' . get_the_permalink( $download ) . '">' . get_the_title( $download ) . '</a>',
                $download_categories,
                $download_tags,
                edd_price( $download_id, false ),
                $excerpt,
                edd_get_purchase_link( array( 'download_id' => $download_id ) )
            );

            $content = str_replace( $template_tags, $replacement, $content );
        }

        return $content;
    }
}