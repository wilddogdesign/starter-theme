<?php

/**
 * Get the slug of the post/postID
 *
 * @param [array/int] $post
 * @return [string]
 */
function getTheSlug($post)
{
    // Check if post object or id
    if (is_array($post)) {
        return $post->post_name;
    } else {
        return get_post_field('post_name', $post);
    }

    return false;
}
