<?php

/**
 * Get an array of post IDs from a relationship field
 *
 * @param [array] $array
 * @return void
 */
function getArrayIdsFromRelationshipField($relationshipField)
{
    $arrayIDs = [];
    if ($relationshipField) {
        foreach ($relationshipField as $post) {
            if (is_object($post)) {
                $arrayIDs[] = $post->ID;
            } else {
                $arrayIDs[] = $post;
            }
        }
    }

    return $arrayIDs;
}
