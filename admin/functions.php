<?php

require_once('savePostActions.php');
require_once('updateTransients.php');
require_once('duplicatePosts.php');
require_once('featuredThumbnailPositioning.php');
require_once('customWYSIWYGs.php');
require_once('setupForms.php'); // TODO RUN ON FORM ENTRY PAGE

/**
 * Admin Menu
 */
require_once('adminMenu.php');
require_once('adminBar.php');

/**
 * Admin page enhancements
 */
require_once('pages/index.php');

/**
 * Admin JS and CSS
 */
function includeJS()
{
    // Lets me use Vue JS in admin
    // wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2' . (WP_ENV == 'development' ? '/dist/vue.js' : ''));

    wp_enqueue_script('sortable-js', 'https://cdn.jsdelivr.net/npm/@shopify/draggable@1.0.0-beta.11/lib/sortable.js');

    // global JS variables can be set here
    // $script  = 'window.apiURL = "' . API_URL . '"; ';
    // $script  .= 'window.apiKey = "' . API_KEY . '"; ';
    // wp_add_inline_script('js-file', $script, 'before');

    wp_enqueue_script('js-file', get_template_directory_uri() . '/admin/admin.js', array(), '1.0.4', true);
}

add_action('admin_enqueue_scripts', 'includeJS');

function includeCss()
{
    wp_enqueue_style('css-file', get_template_directory_uri() . '/admin/admin.css', array(), '1.0.0');
}

add_action('admin_head', 'includeCss');

/**
 * Admin pages hooks
 */

// Remove all support for comments & trackbacks from all post types
function disableCommentsPostTypesSupport()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'disableCommentsPostTypesSupport');

/**
 * ACF Hooks
 */

// Setup Google Maps
function registerACFGoogleMapAPIKey($api)
{
    $api['key'] = get_field('google__maps_api_key', 'options');
    return $api;
}
// add_filter('acf/fields/google_map/api', 'registerACFGoogleMapAPIKey');

require_dir('acf-dynamic-fields'); // Dynamic ACF Fields
