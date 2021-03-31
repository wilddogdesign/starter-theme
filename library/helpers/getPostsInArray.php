<?php

/**
 * Get the Posts in the array of IDs passed in
 *
 * @param [array] $selectedPosts
 * @param [array] $params
 * @return array
 */
function getPostsInArray($selectedPosts, $postType, $params = false)
{
    $postsPerPage = $params['posts_per_page'] ?: -1;
    $pagedNumber = $params['paged'] ?: 1;

    $args = [
        'post_type' => $postType,
        'post_status' => 'publish',
        'paged' => $pagedNumber,
        'post__in' => $selectedPosts,
        'posts_per_page' => $postsPerPage
    ];

    $args['orderby'] = 'publish_date';
    $args['order'] = 'DESC';

    return new Timber\PostQuery($args);
}
