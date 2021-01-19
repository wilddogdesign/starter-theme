<?php

/**
 * Add an async tag to our main-js
 *
 * https://matthewhorne.me/defer-async-wordpress-scripts
 *
 * @param string $tag
 * @param string $handle
 * @return string
 */
function add_async_attribute($tag, $handle)
{
    if ('main-js' !== $handle) {
        return $tag;
    }

    return str_replace(' src', ' async src', $tag);

    // COOKIEBOT EXAMPLE - https://support.cookiebot.com/hc/en-us/articles/360009063660-Disable-automatic-cookie-blocking-for-a-specific-script
    // return str_replace(' src', ' data-cookieconsent="ignore" async src', $tag);
}

add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

/**
 * Manage Scripts
 *
 * @return void
 */
function manageScripts()
{
    if (!is_admin()) {
        // get the filename of main since it can change itself
        // glob is a bit of a weird one i know but there'll only be one main js in there
        $fileLocation = glob('app/themes/bedrock-theme/static/js/main-*.js')[0];
        $jsFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);

        // register main scripts in footer
        wp_register_script('main-js', get_stylesheet_directory_uri() . '/static/js/' . $jsFile, false, null, true);

        // queue them up
        wp_enqueue_script('main-js');

        // nonce
        wp_localize_script('main-js', 'wpApiSettings', [
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ]);

        // remove WP embeds
        wp_deregister_script('wp-embed');

        // dequeue jQuery...actually no we can't because of some plugins!
        // if (!is_admin_bar_showing()) {
        //     wp_deregister_script('jquery');
        //     wp_register_script('jquery', false);
        //     // wp_dequeue_script('jquery');
        //     // wp_dequeue_script('jquery-form');
        // }

        // Remove Gutenberg Block Library CSS from loading on the frontend
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS

        // remove annoying WP emoji galore
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
    }
}

add_action('wp_enqueue_scripts', 'manageScripts', 999);
