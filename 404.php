<?php

/**
 * The template for displaying 404 pages (Not Found)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$context['fourZeroFourTitle'] = get_field('404_title', 'options');
$context['fourZeroFourContent'] = get_field('404_content', 'options');

Timber::render('404.twig', $context);
