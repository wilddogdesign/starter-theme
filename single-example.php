<?php

/**
 * Front End Template: index.njk - https://abc.html
 */

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;

Timber::render('single-example.twig', $context);
