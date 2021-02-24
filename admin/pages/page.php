<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('manage_pages_columns', 'setColumns');
add_action('manage_pages_custom_column', 'populateCustomColumns', 5, 2);
add_action('restrict_manage_posts', 'setupFilters', 10, 2);
add_filter('parse_query', 'filterResults');
add_action('admin_head', 'addCustomColumnCSS');

function setColumns($defaults)
{
    $defaults = array(
        'cb'            => $defaults['cb'],
        'image'         => __('Image'),
        'title'         => $defaults['title'],
        'page-layout'   => __('Template'),
        'date'          => $defaults['date'],
    );

    return $defaults;
}

// add filters to the Pages List
function setupFilters($post_type, $which)
{
    // Apply this only on a specific post type
    if ('page' !== $post_type) {
        return;
    }

    // Template Filter
    $values = array();
    $templates = wp_get_theme()->get_post_templates();
    $page_templates = $templates['page'];
    if ($page_templates) {
        $values['default'] = 'Default';
        foreach ($page_templates as $key => $template) {
            $values[$key] = $template;
        }
    }
    if ($values) {
        customFilter($post_type, 'Template', '_wp_page_template', $values);
    }
}

// add action behind the filters on the Pages List
function filterResults($query)
{
    global $post_type;

    customFilterAction($query, $post_type, '_wp_page_template', false);
}
