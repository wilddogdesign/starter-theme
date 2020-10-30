<?php

/**
 * Taxonomy: Example Taxonomy
 * Front End Template: index.njk - https://abc.html
 */

$context = Timber::get_context();
$term = new TimberTerm();
$context['post'] = $term;

Timber::render('taxonomy-example-taxonomy.twig', $context);
