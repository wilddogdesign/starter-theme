<?php
if (!defined('ABSPATH')) {
    exit;
}

class AdminPageFormEntries
{
    public function __construct()
    {
        /* Hooks all required actions by this class */
        add_filter('manage_form_entry_posts_columns', array($this, 'setColumns'));
        add_filter('list_table_primary_column', array($this, 'setPrimaryColumn'), 10, 2);
        add_action('manage_form_entry_posts_custom_column', 'populateCustomColumns', 10, 2);
    }

    // Add additonal columns in Form Entries
    public function setColumns($defaults)
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
    public function setPrimaryColumn($default, $screen)
    {
        if ('form_entry' === $screen) {
            $default = 'name';
        }

        return $default;
    }
}

new AdminPageFormEntries();
