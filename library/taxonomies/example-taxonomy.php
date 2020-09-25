<?php

$example_tax_labels = array(
    'name'                       => _x('Example Taxonomies', 'Taxonomy General Name', 'bedrock'),
    'singular_name'              => _x('Example Taxonomy', 'Taxonomy Singular Name', 'bedrock'),
    'menu_name'                  => __('Example Taxonomies', 'bedrock'),
    'all_items'                  => __('All Example Taxonomies', 'bedrock'),
    'parent_item'                => __('Parent Item', 'bedrock'),
    'parent_item_colon'          => __('Parent Item:', 'bedrock'),
    'new_item_name'              => __('New Example Taxonomy', 'bedrock'),
    'add_new_item'               => __('Add New Example Taxonomy', 'bedrock'),
    'edit_item'                  => __('Edit Example Taxonomy', 'bedrock'),
    'update_item'                => __('Update Example Taxonomy', 'bedrock'),
    'view_item'                  => __('View Example Taxonomy', 'bedrock'),
    'separate_items_with_commas' => __('Separate items with commas', 'bedrock'),
    'add_or_remove_items'        => __('Add or remove News Example Taxonomy', 'bedrock'),
    'choose_from_most_used'      => __('Choose from the most used', 'bedrock'),
    'popular_items'              => __('Popular Example Taxonomies', 'bedrock'),
    'search_items'               => __('Search Example Taxonomies', 'bedrock'),
    'not_found'                  => __('Not Found', 'bedrock'),
    'no_terms'                   => __('No Example Taxonomies', 'bedrock'),
    'items_list'                 => __('Example Taxonomies list', 'bedrock'),
    'items_list_navigation'      => __('Example Taxonomies list navigation', 'bedrock'),
);
$example_tax_rewrite = array(
    'slug'                  => 'example-taxnomies',
    'with_front'            => false,
);
$example_tax_args = array(
    'labels'                => $example_tax_labels,
    'hierarchical'          => true,
    'public'                => true,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'show_in_nav_menus'     => false,
    'show_tagcloud'         => false,
    'rewrite'               => $example_tax_rewrite,
);
register_taxonomy(
    'example-taxonomies',
    array(
        'example',
    ),
    $example_tax_args
);
