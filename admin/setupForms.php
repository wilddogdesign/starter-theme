<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once(get_template_directory() . '/library/forms/registerForms.php');

// Setup Form Fields in ACF
function setupForms()
{
    $fieldKey = registerACFFormFieldGroup(['contact' => 'Contact', 'newsletter' => 'Newsletter']);

    registerACFFormFields('contact', $fieldKey, [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'telephone' => '',
        'message' => 'textarea',
        'newsletter' => 'boolean'
    ]);

    registerACFFormFields('enquiry', $fieldKey, [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'newsletter' => 'boolean'
    ]);
}

add_action('acf/init', 'setupForms');
