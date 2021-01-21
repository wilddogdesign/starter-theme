<?php

/**
 * Front End Template: offline.njk - https://abc.html
 */

$context = Timber::get_context();
$context['offlineTitle'] = get_field('offline_title', 'options');
$context['offlineContent'] = get_field('offline_content', 'options');

Timber::render('offline.twig', $context);
