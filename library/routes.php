<?php

if (!is_admin()) {
    require_once(ABSPATH . 'wp-admin/includes/post.php');
}

////***** SAMPLE *****/////
// Routes::map('destinations/:destination/', function ($params) {
//     $post = get_page_by_path($params['destination'], OBJECT, 'destination');
//     $query = 'page_id=' . $post->ID . '&post_type=destination';
//     Routes::load('single-destination.php', $params, $query);
// });

Routes::map('form-handler', function () {
    Routes::load('library/forms/FormHandler.php');
});

//TODO make sure extra pages go into sitemap
