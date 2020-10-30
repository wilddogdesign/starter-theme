<?php

// Replace these variables
$singlar = 'Example';
$plural = 'Examples';

$labels = array(
    'name'                  => _x($plural, 'Post Type General Name', 'wdd'),
    'singular_name'         => _x($singlar, 'Post Type Singular Name', 'wdd'),
    'menu_name'             => __($plural, 'wdd'),
    'name_admin_bar'        => __($plural, 'wdd'),
    'archives'              => __($plural, 'wdd'),
    'parent_item'           => __('Parent Item', 'wdd'),
    'parent_item_colon'     => __('Parent Item:', 'wdd'),
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
$reWriteRules = array(
    'slug'                  => 'example-taxnomies',
    'with_front'            => false,
);
$args = array(
    'labels'                => $labels,
    'hierarchical'          => true,
    'public'                => true,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'show_in_nav_menus'     => false,
    'show_tagcloud'         => false,
    'rewrite'               => $reWriteRules,
);

// Please use '-' in the name and not underscores
register_taxonomy(
    'example-taxonomies',
    array(
        'example',
    ),
    $args
);
