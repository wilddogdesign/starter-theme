<?php
// The labels used in various places for this custom post type
$example_labels = array(
    'name'                  => _x('Example Post', 'Post Type General Name', 'bedrock'),
    'singular_name'         => _x('Example Post', 'Post Type Singular Name', 'bedrock'),
    'menu_name'             => __('Example Posts', 'bedrock'),
    'name_admin_bar'        => __('Example Posts', 'bedrock'),
    'archives'              => __('Archived Example Posts', 'bedrock'),
    'all_items'             => __('All Example Posts', 'bedrock'),
    'add_new_item'          => __('Add New Example Post', 'bedrock'),
    'add_new'               => __('Add New', 'bedrock'),
    'new_item'              => __('New Example Post', 'bedrock'),
    'edit_item'             => __('Edit Example Post', 'bedrock'),
    'update_item'           => __('Update Example Post', 'bedrock'),
    'view_item'             => __('View Example Post', 'bedrock'),
    'search_items'          => __('Search Example Posts', 'bedrock'),
);

// Rewrite rules for the post type.
$example_rewrite = array(
    'slug'                  => 'example-posts',
    'with_front'            => true,
    'pages'                 => true,
    'feeds'                 => false,
);

// Arguments for creating the post type so labels etc.
$example_args = array(
    'label'                 => __('Example Post', 'bedrock'),
    'description'           => __('Example Post to be displayed under Example Posts', 'bedrock'),
    'labels'                => $example_labels,
    'supports'              => array('title', 'revisions', 'thumbnail'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 36,
    'menu_icon'             => 'dashicons-format-status',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'rewrite'               => $example_rewrite,
    'capability_type'       => 'page',
    'show_in_rest'          => true,
    'rest_base'             => 'example-posts',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
);

// Then we register the post type with the system
register_post_type('example', $example_args);
