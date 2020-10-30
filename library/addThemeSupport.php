<?php

if (!defined('ABSPATH')) {
    exit;
}


function addThemeSupport()
{
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Enable support for Menus
    add_theme_support('menus');

    // Adding excerpt for pages (other post types are done when registered)
    add_post_type_support('page', 'excerpt');

    // Switch default core markup for search form, comment form, and comments to output valid HTML5
    add_theme_support(
        'html5',
        array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        )
    );

    // Enable support for Post Formats
    add_theme_support(
        'post-formats',
        array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
        )
    );
}
