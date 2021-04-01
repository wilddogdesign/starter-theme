<?php

/**
 * Get the global UTMs and return as an array
 *
 * @return array
 */
function getUTMs()
{
    $globalUTMs = get_field('global__utms', 'options');
    $utms = [];
    if ($globalUTMs) {
        foreach ($globalUTMs as $utm) {
            $utms[] = $utm['utm'];
        }
    }

    return $utms;
}
