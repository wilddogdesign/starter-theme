<?php

/**
 * Delete related transient on save/edit/delete post and regenerate
 *
 * @param [type] $post_id
 * @return void
 */
function deleteTransient($post_id)
{
    $postType = get_post_type($post_id);

    switch ($postType) {
        case 'sample':
            delete_transient('sample-transient-name');
            // Reset transient function here
            break;

        default:
            return;
            break;
    }
}

// in case a new post has been created, use save_post or publish_post:
add_action('acf/save_post', 'deleteTransient');

// in case any post has been deleted, use deleted_post:
add_action('deleted_post', 'deleteTransient');
