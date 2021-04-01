<?php

$status = [];

$theme_dir = get_stylesheet_directory();
$json_output_dir = $theme_dir . '/acf-json/';

if (!file_exists($json_output_dir)) {
    die('Create a acf-json/ directory in your current theme.');
}

$fieldgroups = glob($theme_dir . '/acf-dynamic-fieldgroups/*');
$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

foreach ($fieldgroups as $fg) {

    if (file_exists($fg)) {
        // Include $fg into current context
        // this will create a $group var
        if ($languages) {
            foreach ($languages as $lang => $language) {
                include($fg);
                $json_output_dir = $theme_dir . '/acf-json/' . $lang . '/';
                if (file_put_contents($json_output_dir . 'dynamic__' . $group['key'] . '.json', json_encode($group))) {
                    $status[] = 'Created json file ' . $group['key'] . ' for ' . $group['title'] . '.';
                }
            }
        }
    }
}

// if (count($status)) {
//     echo implode("<br/>", $status);
// } else {
//     echo 'No ACFJson files created.';
// }

// die();
