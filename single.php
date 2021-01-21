<?php

/**
 * Front End Template: index.njk - https://abc.html
 */

$context = Timber::context();
$post = Timber::query_post();
$context['post'] = $post;

Timber::render('single.twig', $context);
