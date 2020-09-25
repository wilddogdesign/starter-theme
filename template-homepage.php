<?php

/**
 * Template Name: Homepage Template
 * Template Post Type: page
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render('template-homepage.twig', $context);
