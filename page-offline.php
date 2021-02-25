<?php

/**
 * Front End Template: offline.njk - https://abc.html
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render('offline.twig', $context);
