<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once(get_template_directory() . '/library/helpers/getUTMs.php');

// Prepare utm fields
$globalUTMs = getUTMs();
$utms = [];
if ($globalUTMs) {
    foreach ($globalUTMs as $utm) {
        $utms[$utm] = 'small';
    }
}

// Setup Form Fields in ACF
$forms = [
    'contact' => array_merge($utms, [
        'name' => '',
        'company' => '',
        'email' => '',
        'telephone' => '',
        'details' => ''
    ]),
    'enquiry' => array_merge($utms, [
        'name' => '',
        'company' => '',
        'email' => '',
        'telephone' => '',
        'details' => ''
    ])
];
