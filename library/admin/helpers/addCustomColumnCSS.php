<?php

if (!defined('ABSPATH')) {
    exit;
}

function addCustomColumnCSS()
{
    echo "
        <style>
            .manage-column.column-image { width: 100px; }
        </style>";
}
