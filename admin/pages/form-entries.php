<?php

if (!defined('ABSPATH')) {
    exit;
}

/* Hooks all required actions by this class */
add_filter('manage_form_entry_posts_columns', 'setColumns');
add_filter('list_table_primary_column', 'setPrimaryColumn', 10, 2);
add_action('manage_form_entry_posts_custom_column', 'populateCustomColumns', 10, 2);

// Add additonal columns in Form Entries
function setColumns($defaults)
{
    unset($defaults['title']);

    $defaults = array(
        'cb'                        => $defaults['cb'],
        'formID'                    => __('Title'),
        'formType'                  => __('Form'),
        'formSubmissionDate'        => __('Submission Date'),
        'formField_contact_name'    => __('Name'),
        'formField_email'           => __('Email')
    );

    return $defaults;
}

// Set default column
function setPrimaryColumn($default, $screen)
{
    if ('form_entry' === $screen) {
        $default = 'name';
    }

    return $default;
}
