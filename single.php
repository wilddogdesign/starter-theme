<?php

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;

Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig'), $context);
