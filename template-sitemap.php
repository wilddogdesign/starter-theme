<?php

/**
 * Template Name: Sitemap Template
 * Template Post Type: page
 * Front End Template: index.njk - https://abc.html
 */

require_once('library/helpers/generateSitemap.php');

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

$context['sitemap'] = generateSitemap();

Timber::render('template-sitemap.twig', $context);
