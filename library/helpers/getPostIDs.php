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

        // Cannot use global $wpdb here as with WPML it suppresses the filters needed.
        $data = wp_list_pluck(get_pages([
            'post_type'         => $post_type,
            'post_status'       => 'publish',
            'sort_column'       => 'title',
        ]), 'ID');

        set_transient($key, $data, $expiry);
    }

    return $data;
}
