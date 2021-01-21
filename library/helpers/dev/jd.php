<?php

/**
 * Return $anything as json to the browser
 *
 * @param [type] $anything
 * @return void
 */
function jd($anything)
{
    if (count(func_get_args()) > 1) {
        $anything = func_get_args();
    }

    header('Content-Type: application/json');

    echo json_encode($anything);

    die;
}
