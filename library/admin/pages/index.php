<?php

if (!defined('ABSPATH')) {
    exit;
}

global $pagenow;

if ($pagenow === 'edit.php' && !empty($_GET['post_type'])) {
    // load common filter functions that can be used on all admin pages below.
    require_once('filters.php');
    require_once('populateCustomColumns.php');
    require_once('addCustomColumnCSS.php');

    switch ($_GET['post_type']) {
        case 'page':
            require_once('page.php');
            break;
        case 'form_entry':
            require_once('form-entries.php');
            break;
    }
}
