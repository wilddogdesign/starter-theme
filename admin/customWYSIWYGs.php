<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('mce_buttons_2', 'addStyleSheetButton'); // Enable the 'Style Select' dropdown
add_filter('tiny_mce_before_init', 'customStyleSheetDropdownOptions');
add_filter('acf/fields/wysiwyg/toolbars', 'defineCustomToolbars'); // Define
add_action('admin_init', 'customEditorStyles'); // Customise Visuals
add_filter('quicktags_settings', 'removeQuicktags', 10, 2);
// add_filter('tiny_mce_before_init', 'customiseTinyMCE');

// Callback function to insert 'styleselect' into the $buttons array
function addStyleSheetButton($buttons)
{
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

function customStyleSheetDropdownOptions($toolbars)
{
    $style_formats = [
        // These are the custom styles - add as many as you like!

        // // An example object with all available properties:
        // [
        //     'title' => 'Title',
        //     'block' => 'h1',
        //     'inline' => 'b',
        //     'classes' => 'the-class',
        //     'styles' => [ 'font-weight' => 'bold' ],
        //     'selector' => ''
        // ]

        // / Repeat this for a tree menu:
        [
            'title' => 'Headers',
            'items' => [
                // Repeat this for sub items:
                // [
                //     'title' => 'Header 1',
                //     'block' => 'h1',
                // ],
                [
                    'title' => 'Header 2',
                    'block' => 'h2'
                ],
                [
                    'title' => 'Header 3',
                    'block' => 'h3'
                ],
                [
                    'title' => 'Header 4',
                    'block' => 'h4'
                ]
                // ...
            ]
        ],
        // Repeat this for a top level style:
        [
            'title' => 'Paragraph',
            'block' => 'p'
        ],
    ];

    // Insert the array, JSON ENCODED, into 'style_formats'
    $toolbars['style_formats'] = json_encode($style_formats);

    // unset the preview styles - show without styling instead
    unset($toolbars['preview_styles']);

    return $toolbars;
}

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
    // $toolbars['Formats Bold Italic Links Only'][1] = array('styleselect', 'bold', 'italic', 'link');
    $toolbars['Formats Bold Italic Links Lists Only'][1] = array('styleselect', 'bold', 'italic', 'bullist', 'numlist', 'link');

    return $toolbars;
}

// Add CSS to WYSIWYG
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

// Remove Buttons on 'text' tab
function removeQuicktags($qtInit, $editor_id = 'content')
{
    // $qtInit['buttons'] = 'strong,em,link,block,del,img,ul,ol,li,code,more,spell,close,fullscreen'; DEFAULT
    $qtInit['buttons'] = ' ';
    return $qtInit;
}
