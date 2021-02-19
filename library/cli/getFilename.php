<?php

namespace WP_CLI;

use WP_CLI;
// use WP_CLI\Process;
// use WP_CLI\Utils;

function getFilename($args)
{
    $filename = sanitize_file_name($args[0]);

    WP_CLI::line($filename);
}
