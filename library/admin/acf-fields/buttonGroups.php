<?php

if (!defined('ABSPATH')) {
    exit;
}

// add_filter('acf/load_field/name=autop__job_roles', 'jobRoles');

// function jobRoles($field)
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
