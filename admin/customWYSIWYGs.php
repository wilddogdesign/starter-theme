<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('acf/fields/wysiwyg/toolbars', 'defineCustomToolbars'); // Define
add_action('admin_init', 'customEditorStyles'); // Customise Visuals
add_filter('quicktags_settings', 'removeQuicktags', 10, 2);

// FULL TOOLBAR:
// formatselect
// bold
// italic
// bullist
// numlist
// blockquote
// alignleft
// aligncenter
// alignright
// link
// wp_more
// spellchecker
// fullscreen
// wp_adv
// strikethrough
// hr
// forecolor
// pastetext
// removeformat
// charmap
// outdent
// indent
// undo
// redo
// wp_help

/**
 * Define custom toolbars
 *
 * @param [array] $toolbars
 * @return void
 */
function defineCustomToolbars($toolbars)
{
    // remove the 'Basic' toolbar completely
    unset($toolbars['Basic']);

    // Add a new toolbar
    $toolbars['Links Only'][1] = array('link');
    $toolbars['Bold Italic Links Only'][1] = array('bold', 'italic', 'link');
    // $toolbars['Formats Bold Italic Links Only'][1] = array('formatselect', 'bold', 'italic', 'link');
    $toolbars['Formats Bold Italic Links Lists Only'][1] = array('formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link');

    return $toolbars;
}

function customEditorStyles()
{
    // CSS
    $base_dir = trailingslashit(get_template_directory());
    $cssFiles = glob($base_dir . 'static/css/main-*.css');

    // remove critical as it also has main- in it's name now
    foreach ($cssFiles as $key => $filename) {
        $fileLocation = array_values($cssFiles)[$key];
        $cssFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);
        $static = get_stylesheet_directory_uri() . '/static/';
        $css = $static . 'css/' . $cssFile;

        add_editor_style($css);
    }

    // Custom Styles for WYSIWYG Only
    add_editor_style('admin/assets/customEditorStyles.css');
}

function removeQuicktags($qtInit, $editor_id = 'content')
{
    // $qtInit['buttons'] = 'strong,em,link,block,del,img,ul,ol,li,code,more,spell,close,fullscreen'; DEFAULT
    $qtInit['buttons'] = ' ';
    return $qtInit;
}
