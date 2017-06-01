<?php
/**
 * Scripts
 *
 * @package     uFramework\Tsunoa\Admin
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( ! function_exists( 'tsunoa_admin_menu' ) ) {
    add_action('admin_menu', 'tsunoa_admin_menu');
    function tsunoa_admin_menu() {
        add_menu_page( 'Tsunoa', 'Tsunoa', 'manage_options', 'tsunoa', 'tsunoa_admin_page', 'dashicons-share-alt', 50 );
    }
}

if( ! function_exists( 'tsunoa_admin_page' ) ) {
    function tsunoa_admin_page() {
        ?>
        <div class="wrap">
            <h1>Tsunoa Dashboard</h1>

            <div id="tsunoa-welcome-panel" class="welcome-panel">
                <div class="welcome-panel-content">

                    <a href="https://tsunoa.com" target="_blank">
                        <img src="<?php echo TSUNOA_URL . 'assets/img/64x64.png' ?>" alt="Tsunoa" class="tsunoa-logo">
                    </a>
                    <h2>Welcome to Tsunoa's dashboard!</h2>
                    <p class="about-description">We have assembled useful links to get you started:</p>

                    <div class="welcome-panel-column-container">

                        <div class="welcome-panel-column">
                            <h3>More plugins</h3>
                            <ul>
                                <li><a href="https://profiles.wordpress.org/tsunoa" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-wordpress"></i> Tsunoa on WordPress</a></li>
                                <li><a href="<?php echo tsunoa_url(); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-admin-site"></i> tsunoa.com</a></li>
                            </ul>
                        </div>

                        <div class="welcome-panel-column">
                            <h3>Contact us</h3>
                            <ul>
                                <li><a href="mailto:contact@tsunoa.com" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-email-alt"></i> contact@tsunoa.com</a></li>
                                <li><a href="<?php echo tsunoa_support_url(); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-sos"></i> Support</a></li>
                            </ul>
                        </div>

                        <div class="welcome-panel-column welcome-panel-last">
                            <h3>Follow us</h3>
                            <ul class="tsunoa-social">
                                <li><a href="https://twitter.com/tsunoa_" target="_blank" class="social-twitter"><i class="dashicons dashicons-twitter"></i> @tsunoa_</a></li>
                                <li><a href="https://www.facebook.com/tsunoa/" target="_blank" class="social-facebook"><i class="dashicons dashicons-facebook-alt"></i> Facebook</a></li>
                                <li><a href="https://plus.google.com/109472317291474743288" target="_blank" class="social-googleplus"><i class="dashicons dashicons-googleplus"></i> Google+</a></li>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>

            <div class="tsunoa-plugins">
                <?php

                $active_plugins = (array) get_option( 'active_plugins', array() );

                foreach( get_plugins() as $plugin_path => $plugin ) : ?>
                    <?php if( $plugin['Author'] == 'Tsunoa' && $plugin['AuthorURI'] == 'https://tsunoa.com' && in_array( $plugin_path, $active_plugins ) ) : ?>
                        <?php $from_wordpress = ( strpos( $plugin["PluginURI"], 'wordpress.org' ) !== false ); ?>
                        <?php $plugin_name = $plugin['TextDomain']; ?>
                        <?php
                        // Filterable values
                        $has_premium_version = apply_filters( "tsunoa_{$plugin_name}_has_premium_version", false );
                        $settings = apply_filters( "tsunoa_{$plugin_name}_settings", false );
                        ?>

                        <div class="tsunoa-plugin <?php echo ( ( $from_wordpress ) ? 'from-wordpress' : '' ); ?> ">
                            <div class="postbox">
                                <h2>
                                    <span><?php echo $plugin['Title']; ?></span>
                                    <small><?php echo $plugin['Version']; ?></small>
                                </h2>
                                <div class="inside">
                                    <p><?php echo $plugin["Description"]; ?></p>

                                    <?php if( ! $from_wordpress ) : ?>

                                        <?php
                                        $license = uframework_get_option( $plugin_name, 'license_key', '' );
                                        $details  = uframework_get_option( $plugin_name, 'license_active', false );
                                        $active  = ( is_object( $details ) &&  $details->license == 'valid' ) ? true : false;
                                        $action = 'activate';

                                        if( $active ) {
                                            $action = 'deactivate';
                                            $license = substr_replace( $license, str_repeat( '*', strlen( $license ) - 8 ), 4, -4 );
                                        }
                                        ?>

                                        <form method="post" action="" class="tsunoa-license-form">

                                            <?php wp_nonce_field( $plugin_name . '-license-nonce', $plugin_name . '-license-nonce' ); ?>

                                            <input type="text" id="<?php echo $plugin_name; ?>-license-key" name="<?php echo $plugin_name; ?>-license-key" value="<?php echo $license; ?>" class="regular-text" placeholder="License key" <?php if( $active ) : ?>readonly<?php endif; ?>>

                                            <input type="hidden" name="action" value="tsunoa_<?php echo $action; ?>_license">
                                            <input type="submit" id="<?php echo $plugin_name . '-' . $action; ?>" name="<?php echo $plugin_name . '-' . $action; ?>" value="<?php echo ucfirst($action); ?> license" class="button-primary">
                                            <span class="spinner"></span>
                                        </form>
                                    <?php elseif( $has_premium_version ) : ?>
                                        <a href="<?php echo tsunoa_product_url( $plugin_name ); ?>" target="_blank" class="button-primary"> Get support and pro features</a>
                                    <?php endif; ?>
                                </div>
                                <div class="actions">
                                    <a href="<?php echo $plugin["PluginURI"]; ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-<?php if( $from_wordpress ) : ?>wordpress<?php else : ?>admin-site<?php endif; ?>"></i> Plugin site</a>

                                    <?php if( ! $from_wordpress || $has_premium_version ) : ?>
                                        <a href="<?php echo tsunoa_product_docs_url( $plugin_name ); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-media-text"></i> Documentation</a>
                                    <?php endif; ?>

                                    <?php if( ! $from_wordpress ) : ?>
                                        <a href="<?php echo tsunoa_support_url(); ?>" target="_blank" class="uframework-icon-link"><i class="dashicons dashicons-sos"></i> Support</a>
                                    <?php endif; ?>

                                    <?php if( $settings !== false ) : ?>
                                        <a href="<?php echo $settings; ?>" class="uframework-icon-link"><i class="dashicons dashicons-admin-generic"></i> Settings</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}