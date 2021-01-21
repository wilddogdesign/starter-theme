<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('acf/load_field/name=autop__image_position', 'imagePositions');

function imagePositions($field)
{
    $field['choices'] = array(
        'center'        => 'Center',
        'top'           => 'Top',
        'bottom'        => 'Bottom',
        'left'          => 'Left',
        'right'         => 'Right',
    );

    return $field;
}
