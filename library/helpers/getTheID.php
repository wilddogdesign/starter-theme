<?php

/**
 * Get the id of the post/postID
 *
 * @param [array/int] $post
 * @return [string]
 */
function getTheID($post)
{
    // Check if post object or id
    if (is_array($post)) {
        return $post->id;
    } else {
        return $post;
    }

    return false;
}
