<?php
if (!defined('ABSPATH')) {
    exit;
}

class AutopCheckboxes
{

    public function __construct()
    {
        /* Hooks all ACF Load Field Hooks required actions by this class */
        // add_filter('acf/prepare_field/name=autop__awards_to_show', array($this, 'awards'), 10, 3);
    }

    // public function awards($field)
    // {
    //     $field['choices'] = false; // Reset Choices
    //     $awards = get_field('global_awards', 'options');

    //     // If there are awards, add them as options in the checkbox list
    //     if ($awards) {
    //         foreach ($awards as $award) {
    //             $field['choices'][$award['id']] = $award['place'] . ' ' . $award['award'];
    //         }
    //     }

    //     return $field;
    // }
}

new AutopCheckboxes();
