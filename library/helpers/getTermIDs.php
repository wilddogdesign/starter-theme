<?php

/**
 * Get all term_id's of taxonomy
 *
 * @param string $taxonomy
 * @return array
 */
function getTermIDs($taxonomy)
{
    $key = '';
    switch ($taxonomy) {
        case 'example-category':
            $key = 'example-category-ids';
            $expiry = WEEK_IN_SECONDS;
            break;
    }

    if (false === ($data = get_transient($key))) { // Get any existing copy of our transient data
        // It wasn't there, so do query and save the transient

        $data = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        set_transient($key, $data, $expiry);
    }

    return $data;
}
