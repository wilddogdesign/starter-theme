<?php

/**
 * Detect plugin. For use on Front End only.
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

$themeDir = get_stylesheet_directory();
$jsonOutputDir = $themeDir . '/acf-json/';

if (!file_exists($jsonOutputDir)) {
    die('Create a acf-json/ directory in your current theme.');
}

$fieldgroups = glob($themeDir . '/acf-dynamic-fieldgroups/*');

if ($fieldgroups) {
    if (class_exists('SitePress')) {
        //WPML is activated
        $languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

        if ($languages) {
            foreach ($languages as $lang => $language) {
                $jsonOutputDir = $themeDir . '/acf-json/' . $lang . '/';

                if (!file_exists($jsonOutputDir)) {
                    die('Create a language-specific acf-json/ subfolder directory in your current theme.');
                }

                foreach ($fieldgroups as $fg) {
                    if (file_exists($fg)) {
                        // Include $fg into current context
                        // this will create a $group var
                        include $fg;

                        $jsonFile = fopen($jsonOutputDir . 'dynamic__' . $group['key'] . '.json', 'w');
                        fwrite($jsonFile, json_encode($group));
                        fclose($jsonFile);
                    }
                }
            }
        }
    } else {
        $jsonOutputDir = $themeDir . '/acf-json/';

        if (!file_exists($jsonOutputDir)) {
            die('Create a lacf-json/ subfolder directory in your current theme.');
        }

        foreach ($fieldgroups as $fg) {
            if (file_exists($fg)) {
                // Include $fg into current context
                // this will create a $group var
                include $fg;

                $jsonFile = fopen($jsonOutputDir . 'dynamic__' . $group['key'] . '.json', 'w');
                fwrite($jsonFile, json_encode($group));
                fclose($jsonFile);
            }
        }
    }
}
