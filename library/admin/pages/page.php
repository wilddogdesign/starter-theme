<?php
if (!defined('ABSPATH')) {
    exit;
}

class AdminPagePage
{
    public function __construct()
    {
        /* Hooks all required actions by this class */
        add_filter('manage_pages_columns', array($this, 'setColumns'));
        add_action('manage_pages_custom_column', array($this, 'populateColumns'), 5, 2);
        add_action('restrict_manage_posts', array($this, 'setupFilters'), 10, 2);
        add_filter('parse_query', array($this, 'filterResults'));
        add_action('admin_head', array($this, 'customCSS'));
    }

    public function setColumns($defaults)
    {
        $defaults = array(
            'cb'            => $defaults['cb'],
            'image'         => __('Image'),
            'title'         => $defaults['title'],
            'page-layout'   => __('Template'),
            'date'          => $defaults['date'],
        );

        return $defaults;
    }

    public function populateColumns($column_name, $id)
    {
        switch ($column_name) {
            case 'image':
                // Image column
                echo get_the_post_thumbnail($id, array(80, 80));
                break;
            case 'page-layout':
                // Template column
                $set_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
                if ($set_template == 'default') {
                    echo 'Default';
                }
                $templates = get_page_templates();
                ksort($templates);
                foreach (array_keys($templates) as $template) :
                    if ($set_template == $templates[$template]) {
                        echo $template;
                    }
                endforeach;
                break;
        }
    }

    // add filters to the Pages List
    public function setupFilters($post_type, $which)
    {
        // Apply this only on a specific post type
        if ('page' !== $post_type) {
            return;
        }

        // Template Filter
        $values = array();
        $templates = wp_get_theme()->get_post_templates();
        $page_templates = $templates['page'];
        if ($page_templates) {
            $values['default'] = 'Default';
            foreach ($page_templates as $key => $template) {
                $values[$key] = $template;
            }
        }
        if ($values) {
            AdminPageFilters::customFilter($post_type, 'Template', '_wp_page_template', $values);
        }
    }

    // add action behind the filters on the Pages List
    public function filterResults($query)
    {
        global $post_type;

        AdminPageFilters::customFilterAction($query, $post_type, '_wp_page_template', false);
    }


    // Customise the column styles
    public function customCSS()
    {
        global $post_type;

        // Apply this only on a specific post type
        if ('page' !== $post_type) {
            return;
        }

        echo "
        <style>
            .manage-column.column-image { width: 100px; }
        </style>";
    }
}

new AdminPagePage();
