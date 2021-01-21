<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate a filter select input for the specified taxonomy slug
 *
 * @param [type] $taxonomy_slug
 * @return void
 */
function generateTaxonomyFilter($taxonomy_slug)
{
    if (!$taxonomy_slug) {
        return; // return if empty
    }

    $filter = false;

    // Retrieve taxonomy data
    $taxonomy_obj = get_taxonomy($taxonomy_slug);
    $taxonomy_name = $taxonomy_obj->labels->name;

    // Retrieve taxonomy terms
    $terms = get_terms($taxonomy_slug);

    // Display filter HTML
    $filter = "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
    $filter = $filter . '<option value="">' . sprintf(esc_html__('%s', 'text_domain'), $taxonomy_name) . '</option>';

    foreach ($terms as $term) {
        $filter = $filter .
            '<option value="' . $term->slug . '" ' . ((isset($_GET[$taxonomy_slug]) && ($_GET[$taxonomy_slug] == $term->slug)) ? ' selected="selected"' : '') . '>' . $term->name . ' (' . $term->count . ')</option>';
    }

    $filter = $filter . '</select>';

    if ($filter) {
        echo $filter;
    }
}

/**
 * Generate a filter select input based on the specified parameters
 *
 * @param [type] $post_type
 * @param [type] $title
 * @param [type] $meta_key
 * @param [type] $values
 * @return void
 */
function customFilter($post_type, $title, $meta_key, $values)
{
    if (!$post_type || !$title || !$meta_key || !$values) {
        return; // return if empty
    }

    $filter = false;
    $type = 'post';

    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    if ($post_type == $type) {
        // Display filter HTML
        $filter = '<select name="' . $meta_key . '" id="' . $meta_key . '" class="postform">';
        $filter = $filter . '<option value="">Sort by ' . $title . '</option>';
        foreach ($values as $key => $value) {
            $filter = $filter .
                '<option value="' . $key . '" ' . ((isset($_GET[$meta_key]) && ($_GET[$meta_key] == $key)) ? ' selected="selected"' : '') . '>' . $value . '</option>';
        }
        $filter = $filter . '</select>';
    }

    if ($filter) {
        echo $filter;
    }
}

/**
 * Modify the specified admin page query using the supplied parameters
 *
 * @param [type] $query
 * @param [type] $post_type
 * @param [type] $meta_key
 * @param [type] $compare
 * @return void
 */
function customFilterAction($query, $post_type, $meta_key, $compare)
{
    global $pagenow;

    if (!$meta_key || !$compare) {
        return; // return if empty
    }

    $type = 'post';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }

    if ($post_type == $type && is_admin() && $query->is_main_query() && $pagenow == 'edit.php' && isset($_GET[$meta_key]) && $_GET[$meta_key] != '') {
        $query->query_vars['meta_query'][] = array(
            'key'     => $meta_key,
            'value'   => $_GET[$meta_key],
            'compare' => $compare
        );
    }
}
