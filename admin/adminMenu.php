<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', 'addACFOptionsPages');

add_action('admin_menu', 'removeAdminMenuItems');
add_action('admin_menu', 'addAdminMenuItems');
add_action('admin_menu', 'changeAdminMenuParent');
add_action('admin_menu', 'addAdminMenuSeparators');

add_action('admin_bar_menu', 'customDashboardLogo', 40); // Function in adminBar.php.

add_filter('admin_footer_text', 'customFooter');

// Disable Gutenberg editor
add_filter('use_block_editor_for_post', '__return_false');

add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', 'customMenuOrder');

// Add ACF Options page(s)
function addACFOptionsPages()
{
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(
        array(
            'page_title'    => 'Global Options',
            'menu_title'    => 'Global Options',
            'menu_slug'     => 'wdd-global-options',
            'capability'    => 'edit_posts',
            'parent_slug'   => '',
            'position'      => false,
            'icon_url'      => false,
            'redirect'      => false
        )
    );

    if (!function_exists('acf_add_options_sub_page')) {
        return;
    }

    acf_add_options_sub_page([
        'post_id'       => 'global-options',
        'page_title'    => 'General',
        'menu_title'    => 'General',
        'menu_slug'     => 'canvas-general-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'canvas-global-options',
        'redirect'      => false
    ]);

    acf_add_options_sub_page([
        'post_id'       => 'global-contact',
        'page_title'    => 'Contact',
        'menu_title'    => 'Contact',
        'menu_slug'     => 'canvas-contact-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'canvas-global-options',
        'redirect'      => false
    ]);

    acf_add_options_sub_page([
        'post_id'       => 'global-defaults',
        'page_title'    => 'Default Values',
        'menu_title'    => 'Default Values',
        'menu_slug'     => 'canvas-default-values-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'canvas-global-options',
        'redirect'      => false
    ]);

    acf_add_options_sub_page([
        'post_id'       => 'global-pages',
        'page_title'    => 'Pages',
        'menu_title'    => 'Pages',
        'menu_slug'     => 'canvas-pages-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'canvas-global-options',
        'redirect'      => false
    ]);

    acf_add_options_sub_page([
        'post_id'       => 'form-settings',
        'page_title'    => 'Form Settings',
        'menu_title'    => 'Form Settings',
        'menu_slug'     => 'canvas-forms-options',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'edit.php?post_type=form_entry',
        'redirect'      => false
    ]);

    acf_add_options_sub_page([
        'post_id'       => 'smtp',
        'page_title'    => __('SMTP Settings'),
        'menu_title'    => __('SMTP Settings'),
        'menu_slug'     => 'smtp-settings',
        'capability'    => 'edit_posts',
        'parent_slug'   => 'options-general.php',
        'redirect'      => false
    ]);
}

// Hide Menu Items in Admin Menu that we don't want the client seeing
function removeAdminMenuItems()
{
    // Top Level Pages
    remove_menu_page('edit.php');  //Posts
    // remove_menu_page('tools.php');  //Tools
    // remove_menu_page('edit-comments.php'); //Comments - this is done in disableComments.php
    remove_menu_page('link-manager.php'); // Links
    remove_menu_page('themes.php'); // Appearance
    //remove_menu_page('edit.php?post_type=acf-field-group'); //Custom fields

    // Submenu Pages
    remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page'); // Add new
    remove_submenu_page('upload.php', 'media-new.php'); // Add new
    remove_submenu_page('edit.php?post_type=form_entry', 'post-new.php?post_type=form_entry'); // Add new
}

// Add Menu Items in Admin Menu
function addAdminMenuItems()
{
    // add_menu_page(
    //     __('Forms', 'wdd'),
    //     'Forms',
    //     'manage_options',
    //     'wdd-forms',
    //     'customAdminPageContent',
    //     'dashicons-clipboard'
    // );
}

function changeAdminMenuParent()
{
    // Menus
    remove_submenu_page('themes.php', 'nav-menus.php');
    add_submenu_page(
        'wdd-global-options',
        __('Menus', 'wdd'),
        'Menus',
        'manage_options',
        'nav-menus.php',
        '',
        2
    );

    // // Redirects
    // add_submenu_page(
    //     'wdd-global-options',
    //     __('Redirects', 'wdd'),
    //     'Redirects',
    //     'manage_options',
    //     'tools.php?page=redirection.php',
    //     '',
    //     2
    // );

    // // Transients
    // add_submenu_page(
    //     'wdd-global-options',
    //     __('Transients', 'wdd'),
    //     'Transients',
    //     'manage_options',
    //     'tools.php?page=pw-transients-manager',
    //     '',
    //     3
    // );
}

// Add Separators in Admin Menu
function addAdminMenuSeparators()
{
    // Separators
    global $menu;
    $menu[103] = array(0 => '', 1 => 'read', 2 => 'separator3', 3 => '', 4 => 'wp-menu-separator');
    $menu[104] = array(0 => '', 1 => 'read', 2 => 'separator4', 3 => '', 4 => 'wp-menu-separator');
    $menu[105] = array(0 => '', 1 => 'read', 2 => 'separator5', 3 => '', 4 => 'wp-menu-separator');
    $menu[106] = array(0 => '', 1 => 'read', 2 => 'separator6', 3 => '', 4 => 'wp-menu-separator');
    $menu[107] = array(0 => '', 1 => 'read', 2 => 'separator7', 3 => '', 4 => 'wp-menu-separator');
}

/** Reorder Admin Navigation Menu */
function customMenuOrder($menu_ord)
{
    if (!$menu_ord) {
        return true;
    }

    return array(
        'index.php',                                                            // Dashboard
        'separator1',                                                           // Default Separator

        'edit.php?post_type=acf-field-group',                                   // ACF Fields (Hidden upon deployment)
        'wdd-global-options',                                                   // Global Options
        'separator2',                                                           // Default Separator// Custom

        'upload.php',                                                           // Media
        'edit.php?post_type=page',                                              // Pages
        'separator3',                                                           // Default Separator

        'edit.php?post_type=example',                                           // CPT Example
        'separator4',                                                           // Custom Separator

        'edit.php?post_type=form_entry',                                        // Forms & Entries
        'separator5',                                                           // Custom Separator

        'separator6',                                                           // Custom Separator

        'users.php',                                                            // Users
        'separator7',                                                           // Custom Separator

        'options-general.php',                                                  // WP Settings
        'plugins.php',                                                          // Plugins
    );
}

// Custom Form Page
// function customAdminPageContent()
// {
//     esc_html_e('Admin Page Test', 'wdd');
// }

// Custom Backend Footer
function customFooter()
{
    _e('<img alt="logo" src="' . get_bloginfo('template_directory') . '/admin/assets/wild-dog-red.png" style="height: 25px; padding: 3px 0px";/>
		</br>
		<span id="footer-thankyou">Developed by <a href="https://wilddogdesign.co.uk/">Wild Dog Design</a></span>', 'core');
}
