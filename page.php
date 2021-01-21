<?php

/**
 * Front End Template: default.njk - https://abc.html
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render('page.twig', $context);
