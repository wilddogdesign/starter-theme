<?php
if (!defined('ABSPATH')) {
    exit;
}

class AutopSelect
{

    public function __construct()
    {
        /* Hooks all ACF Load Field Hooks required actions by this class */
        add_filter('acf/load_field/name=autop__image_position', array($this, 'imagePositions'));
    }

    public function imagePositions($field)
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
}

new AutopSelect();
