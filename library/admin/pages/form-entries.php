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
        add_action('manage_form_entry_posts_custom_column', array($this, 'populateColumns'), 10, 2);
    }

    // Add additonal columns in Form Entries
    public function setColumns($defaults)
    {
        unset($defaults['title']);

        $defaults = array(
            'cb'            => $defaults['cb'],
            'name'          => __('Title'),
            'form'          => __('Form'),
            'sub_date'      => __('Submission Date'),
            'contact_name'  => __('Name'),
            'email'         => __('Email'),

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

    // Populate the additional columns in Form Entries
    public function populateColumns($column_name, $id)
    {
        switch ($column_name) {
            case 'name':
                // Title column
                echo '<a href="' . get_edit_post_link($id) . '"><strong>Entry: ' . $id . '</strong></a>';
                break;
            case 'form':
                // Form column
                echo get_field('form_type', $id);
                break;
            case 'sub_date':
                // Date column
                echo get_post_meta($id, 'entry_submission_date')[0];
                break;
            case 'contact_name':
                // First Name column
                echo get_field('contact_name', $id);
                break;
            case 'email':
                // Email column
                echo get_field('email', $id);
                break;
        }
    }
}

new AdminPageFormEntries();
