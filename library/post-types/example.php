<?php

// Replace these variables
$singlar = 'Example';
$plural = 'Examples';

// The labels used in various places for this custom post type
$labels = array(
    'name'                  => _x($plural, 'Post Type General Name', 'wdd'),
    'singular_name'         => _x($singlar, 'Post Type Singular Name', 'wdd'),
    'menu_name'             => __($plural, 'wdd'),
    'name_admin_bar'        => __($plural, 'wdd'),
    'archives'              => __($plural, 'wdd'),
    'parent_item_colon'     => __('Parent item:', 'wdd'),
    'all_items'             => __($plural, 'wdd'),
    'add_new_item'          => __('Add a New ' . $singlar, 'wdd'),
    'add_new'               => __('Add New', 'wdd'),
    'new_item'              => __('New ' . $singlar, 'wdd'),
    'edit_item'             => __('Edit ' . $singlar, 'wdd'),
    'update_item'           => __('Update ' . $singlar, 'wdd'),
    'view_item'             => __('View ' . $singlar, 'wdd'),
    'search_items'          => __('Search ' . $plural, 'wdd'),
    'not_found'             => __('Not found', 'wdd'),
    'not_found_in_trash'    => __('Not found in Trash', 'wdd'),
    'insert_into_item'      => __('Insert into item', 'wdd'),
    'uploaded_to_this_item' => __('Uploaded to this item', 'wdd'),
    'items_list'            => __('Items list', 'wdd'),
    'items_list_navigation' => __('Items list navigation', 'wdd'),
    'filter_items_list'     => __('Filter items list', 'wdd'),
);

// Rewrite rules for the post type.
$reWriteRules = array(
    'slug'                  => 'example-posts',
    'with_front'            => true,
);

// Arguments for creating the post type so labels etc.
$args = array(
    'label'                 => __($singlar, 'wdd'),
    'labels'                => $labels,
    'supports'              => array('title', 'revisions', 'thumbnail'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_icon'             => 'dashicons-format-status',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => false,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'rewrite'               => $reWriteRules,
    'capability_type'       => 'page',
    'show_in_rest'          => true,
    'rest_base'             => 'example-posts',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
);

// Then we register the post type with the system
// Please use underscores in the name and not '-' to prevent issues further down the line
register_post_type('example', $args);
