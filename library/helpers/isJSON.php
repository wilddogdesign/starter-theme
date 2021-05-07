<?php

/**
 * Is this $string passed in, in JSON format?
 *
 * @param [type] $string
 * @return boolean
 */
function isJSON($string)
{
    if (is_string($string) && is_array(json_decode(stripslashes($string), true)) && (json_last_error() == JSON_ERROR_NONE)) {
        return true;
    }

    return false;
}
