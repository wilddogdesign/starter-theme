<?php

/**
 * Template Name: Homepage Template
 * Template Post Type: page
 * Front End Template: index.njk - https://abc.html
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render('template-homepage.twig', $context);
