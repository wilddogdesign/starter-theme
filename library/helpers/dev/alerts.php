<?php

global $pagenow;

/**
 * This function adds a yellow banner to the top of the WP dashboard to alert users that they are on the staging site
 */
add_action('admin_notices', function () {
    echo '<div class="notice notice-warning">
             <p>This is your <strong>STAGING</strong> website. This is used to test new features before launch. Updates on this site <strong>will not appear on the live site</strong>.</p>
         </div>';
});

/**
 * This function adds a flashing red banner in the WP admin bar at the top of the page to alert users that they are on the staging site
 */
add_action('admin_bar_menu', 'add_toolbar_items', 100);

function add_toolbar_items($admin_bar)
{
    $admin_bar->add_menu(array(
        'id'    => 'staging-site',
        'title' => 'STAGING SITE',
        'meta'  => array(
            'title' => __('STAGING SITE'),
            'class' => 'staging-site'
        ),
    ));
}

/**
 * This function is normally only called on Admin pages, but we need it on the user site on staging for the flashing red warning
 */
wp_enqueue_style('css-file', get_template_directory_uri() . '/admin/admin.css', array(), '1.0.0');
