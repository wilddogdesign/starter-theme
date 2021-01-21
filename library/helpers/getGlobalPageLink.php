<?php

function getGlobalPageLink($globalField)
{
    $field = get_field($globalField, 'option');
    if ($field) {
        return get_permalink($field);
    }
    return '#';
}
