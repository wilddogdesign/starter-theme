<?php

function getBasicPostDetails($post = false)
{
    if ($post) {
        if (is_numeric($post)) {
            $post = get_post($post);
        }

        return (object) [
            'ID' => $post->ID,
            'post_name' => $post->post_name,
            'thumbnail' => get_post_thumbnail_id($post),
            'thumbnail_position' => $post->thumbnail_position,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'link' => get_permalink($post)
        ];
    }

    return $post;
}
