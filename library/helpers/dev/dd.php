<?php

/**
 * DD (Die Dump)
 * Literally die and dump anything shorthand
 *
 * @param mixed $anything Literally anything, it will be dumped
 *
 * @return void
 */
function dd($anything)
{
    if (count(func_get_args()) > 1) {
        $anything = func_get_args();
    }

    die(var_dump($anything));
}
