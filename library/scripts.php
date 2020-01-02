<?php
// Add an async tag to our main-js
// http://matthewhorne.me/defer-async-wordpress-scripts
function add_async_attribute($tag, $handle)
{
    if ('main-js' !== $handle)
        return $tag;
    return str_replace(' src', ' async src', $tag);
}

// load our scripts
function scripts_and_styles()
{
    if (!is_admin()) {
        // get the filename of main since it can change itself
        // glob is a bit of a weird one i know but there'll only be one main js in there
        $fileLocation = glob('app/themes/bedrock-theme/static/js/main-*.js')[0];
        $jsFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);
        // register modernizr
        // wp_register_script('modernizr', get_stylesheet_directory_uri() . '/static/js/modernizr.custom.js', array(), '1.0', false);
        // register main scripts in footer
        wp_register_script('main-js', get_stylesheet_directory_uri() . '/static/js/' . $jsFile, false, NULL, true);
        // queue them up
        // wp_enqueue_script('modernizr');
        wp_enqueue_script('main-js');
        // remove annoying WP emoji galore
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        wp_localize_script('main-js', 'wpApiSettings', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));
        // remove WP embeds
        wp_deregister_script('wp-embed');
        // dequeue jQuery
        wp_dequeue_script('jquery');
        wp_dequeue_script('jquery-form');
        // dequeue Contact Form 7
        // wp_dequeue_style('contact-form-7-css');
    }
}

add_filter('wpcf7_load_js', '__return_false');
// add a new image size
//add_image_size('blog_thumbnail', 420, 300, true);
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);
add_action('wp_enqueue_scripts', 'scripts_and_styles', 999);
add_filter('shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3);

function custom_shortcode_atts_wpcf7_filter($out, $pairs, $atts)
{
    $my_attr = 'current_page';
    if (isset($atts[$my_attr])) {
        $out[$my_attr] = $atts[$my_attr];
    }
    return $out;
}
