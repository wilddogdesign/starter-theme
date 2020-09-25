<?php

if (!defined('ABSPATH')) {
    exit;
}

/** This is where you can add your own functions to twig.
 *
 * @param string $twig get extension.
 */

class TwigExtensions
{
    public static function addToTwig($twig)
    {
        $twig->addExtension(new Twig_Extension_StringLoader());

        // Add class to paragraphs
        $twig->addFilter('pclass', new Twig_SimpleFilter('pclass', function ($string, $class) {
            return str_replace('<p>', '<p class="' . $class . '">', $string);
        }));

        // Add class to 1st paragraph and another to 2nd paragraph
        $twig->addFilter('pclasslede', new Twig_SimpleFilter('pclasslede', function ($string, $ledeclass, $class2) {
            return str_replace('<p>', '<p class="' . $class2 . '">', preg_replace('!<p>!s', '<p class="' . $ledeclass . '">', $string, 1));
        }));

        return $twig;
    }
}
