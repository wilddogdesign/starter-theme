<?php

if (!defined('ABSPATH')) {
    exit;
}

class CustomWYSIWYGs
{

    public function __construct()
    {
        /* Hooks all required actions by this class */
        add_filter('acf/fields/wysiwyg/toolbars', array($this, 'defineCustomToolbars')); // Define
        add_action('admin_init', array($this, 'customEditorStyles')); // Customise Visuals
        add_filter('quicktags_settings', array($this, 'removeQuicktags'), 10, 2);
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
    public function defineCustomToolbars($toolbars)
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

    public function customEditorStyles()
    {
        add_editor_style('/library/admin/helpers/customEditorStyles.css');
    }

    public function removeQuicktags($qtInit, $editor_id = 'content')
    {
        // $qtInit['buttons'] = 'strong,em,link,block,del,img,ul,ol,li,code,more,spell,close,fullscreen'; DEFAULT
        $qtInit['buttons'] = ' ';
        return $qtInit;
    }
}

new CustomWYSIWYGs();
