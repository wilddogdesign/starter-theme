<?php

/**
 * Front End Template: abc.njk - https://abc.html
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render('page.twig', $context);
