<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_bar_menu', 'customDashboardLogo', 40);
add_action('admin_bar_menu', 'removeItems', 999);

// Hide 'New  Buttons in Admin Header Bar
function removeItems($wp_admin_bar)
{
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_node('new-post');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('customize');
    $wp_admin_bar->remove_node('site-name');
}

// Custom Logo on WP Admin Bar
function customDashboardLogo($wp_admin_bar)
{
    $title = get_bloginfo('name');
    $wp_admin_bar->add_node(
        array(
            'id'    => 'alt-site-name',
            'title' => '<img alt="logo" src="' . get_bloginfo('template_directory') . '/admin/logo.svg" style="height: 22px;margin: 3px 5px;float: left;"/>' . $title,
            'href'  => (is_admin() || !current_user_can('read')) ? home_url('/') : admin_url(),
        )
    );
}
