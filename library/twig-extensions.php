<?php

if (!defined('ABSPATH')) {
    exit;
}

/** This is where you can add your own functions to twig.
 *
 */

$twig->addExtension(new Twig\Extension\StringLoaderExtension());

// Use this function in twig to get option field values, so that all options don't have to be loaded into context.
$twig->addFunction(new \Twig\TwigFunction('get_global', function ($field, $globalID) {
    return get_field($field, $globalID);
}));

// Use this function in twig to get option field values, so that all options don't have to be loaded into context.
$twig->addFunction(new \Twig\TwigFunction('get_option', function ($field, $format_value = true) {
    return get_field($field, 'options', $format_value);
}));

// Use this function in twig to get the link of a post or post_id, so that it doesnt need a whole instance of TimberPost.
$twig->addFunction(new \Twig\TwigFunction('get_link', function ($post) {
    return get_permalink($post);
}));

// Use this function in twig to get the title of a post or post_id, so that it doesnt need a whole instance of TimberPost.
$twig->addFunction(new \Twig\TwigFunction('get_title', function ($post) {
    return get_the_title($post);
}));

// Use this function in twig to get the title of a post or post_id, so that it doesnt need a whole instance of TimberPost.
$twig->addFunction(new \Twig\TwigFunction('get_field', function ($field, $post) {
    return get_field($field, $post);
}));

// Use this function in twig to get the link of a post or post_id, so that it doesnt need a whole instance of TimberPost.
$twig->addFunction(new \Twig\TwigFunction('get_thumbnail', function ($post) {
    return get_post_thumbnail_id($post);
}));
// Add class to paragraphs
$twig->addFilter('pclass', new Twig_SimpleFilter('pclass', function ($string, $class) {
    return str_replace('<p>', '<p class="' . $class . '">', $string);
}));

//add class to first paragraph and another to further paragraphs
$twig->addFilter(new Twig_SimpleFilter('pclass2', function ($string, $class, $class2) {
    return str_replace('<p>', '<p class="' . $class2 . '">', preg_replace('!<p>!s', '<p class="' . $class . '">', $string, 1));
}));

// Add class to 1st paragraph and another to 2nd paragraph
$twig->addFilter('pclasslede', new Twig_SimpleFilter('pclasslede', function ($string, $ledeclass, $class2) {
    return str_replace('<p>', '<p class="' . $class2 . '">', preg_replace('!<p>!s', '<p class="' . $ledeclass . '">', $string, 1));
}));

//add class to div
$twig->addFilter(new Twig_SimpleFilter('divclass', function ($string, $class) {
    return str_replace('<div>', '<div class="' . $class . '">', $string);
}));

//add class to ul
$twig->addFilter(new Twig_SimpleFilter('ulclass', function ($string, $class) {
    return str_replace('<ul>', '<ul class="' . $class . '">', $string);
}));

//add class to li
$twig->addFilter(new Twig_SimpleFilter('liclass', function ($string, $class) {
    return str_replace('<li>', '<li class="' . $class . '">', $string);
}));

//add class to h2
$twig->addFilter(new Twig_SimpleFilter('h2class', function ($string, $class) {
    return str_replace('<h2>', '<h2 class="' . $class . '">', $string);
}));

//add class to h3
$twig->addFilter(new Twig_SimpleFilter('h3class', function ($string, $class) {
    return str_replace('<h3>', '<h3 class="' . $class . '">', $string);
}));

$twig->addFilter(new Twig_SimpleFilter('nl2br', function ($string) {
    return preg_replace("/\r|\n/", "", nl2br($string));
}));

$twig->addFilter(new Twig_SimpleFilter('enableShortcodes', function ($string) {
    // Pass the content in do_shortcode() will process all the shortcodes in it.
    // Lets say $data contains custom field data including shortcodes
    return do_shortcode($string); // will contain processed shortcodes
}));

/**
 * Convert video URL to embed src
 */
$twig->addFilter(new Twig_SimpleFilter('embed', function ($string) {
    if (preg_match('/https:\/\/(?:www.)?youtu(be.com\/watch\?v=|.be\/)(.*?)/', $string)) {
        $string = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$2?controls=1&modestbranding=1&rel=0",
            $string
        );
    } elseif (preg_match('/https:\/\/(?:www.)?(vimeo).com\/(\\d+)/', $string)) {
        $string = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*vimeo.com\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://player.vimeo.com/video/$1?controls=1",
            $string
        );
    }

    return $string;
}));

/**
 * Convert video URL to embed src for background video
 */
$twig->addFilter(new Twig_SimpleFilter('embedBackground', function ($string) {
    if (preg_match('/https:\/\/(?:www.)?youtu(be.com\/watch\?v=|.be\/)(.*?)/', $string)) {
        $string = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://www.youtube.com/embed/$1?controls=0&autoplay=1&modestbranding=1&rel=0&mute=1&playlist=$1&loop=1",
            $string
        );
    } elseif (preg_match('/https:\/\/(?:www.)?(vimeo).com\/(\\d+)/', $string)) {
        $string = preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*vimeo.com\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "https://player.vimeo.com/video/$1?background=1&controls=0&muted=1",
            $string
        );
    }

    return $string;
}));
