<?php
if (!defined('ABSPATH')) {
    exit;
}

class AutopButtonGroup
{
    public function __construct()
    {
        /* Hooks all ACF Load Field Hooks required actions by this class */
        // add_filter('acf/load_field/name=autop__job_roles', array($this, 'jobRoles'));
    }

    // public static function jobRoles($field)
    // {
    //     $field['choices'] = false; // Reset Choices
    //     $job_roles = get_field('global_job_roles', 'options');

    //     if ($job_roles) {
    //         foreach ($job_roles as $role) {
    //             $field['choices'][$role['job_reference']] = $role['job_title'];
    //         }
    //     }

    //     return $field;
    // }
}

new AutopButtonGroup();
