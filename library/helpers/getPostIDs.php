<?php

/**
 * Get all ID's of post type
 *
 * @param [string] $post_type
 * @return array
 */
function getPostIDs($post_type)
{
    $key = '';
    switch ($post_type) {
        case 'page':
            $key = 'page-ids';
            $expiry = WEEK_IN_SECONDS;
            break;
    }

    if (false === ($data = get_transient($key))) { // Get any existing copy of our transient data
        // It wasn't there, so do query and save the transient

        if (class_exists('SitePress')) {
            //WPML is activated
            // Cannot use global $wpdb here as with WPML it suppresses the filters needed.
            $data = wp_list_pluck(get_pages([
                'post_type'         => $post_type,
                'post_status'       => 'publish',
                'sort_column'       => 'title',
            ]), 'ID');
        } else {
            global $wpdb;

            $data = wp_list_pluck($wpdb->get_results("
                SELECT ID FROM wp_posts
                WHERE post_status = 'publish' AND post_type='$post_type' ORDER BY post_title
            "), 'ID');
        }

        set_transient($key, $data, $expiry);
    }

    return $data;
}
