<?php

if (!defined('ABSPATH')) {
    exit;
}

function populateCustomColumns($column_name, $id)
{
    switch ($column_name) {
        case 'image':
            // Image column
            echo get_the_post_thumbnail($id, array(80, 80));
            break;
        case 'page-layout':
            // Template column
            $set_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
            if ($set_template == 'default') {
                echo 'Default';
            }
            $templates = get_page_templates();
            ksort($templates);
            foreach (array_keys($templates) as $template) :
                if ($set_template == $templates[$template]) {
                    echo $template;
                }
            endforeach;
            break;
        case 'formID':
            // Title column
            echo '<a href="' . get_edit_post_link($id) . '"><strong>Entry: ' . $id . '</strong></a>';
            break;
        case 'formType':
            // Form column
            echo get_field('form_type', $id);
            break;
        case 'formSubmissionDate':
            // Date column
            echo get_post_meta($id, 'submission_date')[0];
            break;
        case 'formField_contact_name':
            // First Name column
            echo get_field('contact_name', $id);
            break;
        case 'formField_email':
            // Email column
            echo get_field('email', $id);
            break;
    }
}
