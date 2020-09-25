<?php
if (!defined('ABSPATH')) {
    exit;
}

class StarterSiteAdminMenu
{
    public function __construct()
    {
        add_action('acf/init', array($this, 'addACFOptionsPages'));

        add_action('admin_menu', array($this, 'removeAdminMenuItems'));
        add_action('admin_menu', array($this, 'addAdminMenuItems'));
        add_action('admin_menu', array($this, 'changeAdminMenuParent'));
        add_action('admin_menu', array($this, 'addAdminMenuSeparators'));

        add_action('admin_bar_menu', array($this, 'customDashboardLogo'), 40);

        add_filter('admin_footer_text', array($this, 'customFooter'));

        // Disable Gutenberg editor
        add_filter('use_block_editor_for_post', '__return_false');

        add_filter('custom_menu_order', '__return_true');
        add_filter('menu_order', array($this, 'customMenuOrder'));

        add_action('admin_head', array($this, 'customMenuStyles'));
    }

    // Add ACF Options page(s)
    public function addACFOptionsPages()
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
                'redirect'      => false
            )
        );

        if (!function_exists('acf_add_options_sub_page')) {
            return;
        }

        acf_add_options_sub_page(
            array(
                'page_title'    => 'General',
                'menu_title'    => 'General',
                'menu_slug'     => 'wdd-general-options',
                'capability'    => 'edit_posts',
                'parent_slug'   => 'wdd-global-options',
                'redirect'      => false
            )
        );

        acf_add_options_sub_page(
            array(
                'page_title'    => 'Contact',
                'menu_title'    => 'Contact',
                'menu_slug'     => 'wdd-contact-options',
                'capability'    => 'edit_posts',
                'parent_slug'   => 'wdd-global-options',
                'redirect'      => false
            )
        );

        acf_add_options_sub_page(
            array(
                'page_title'    => 'Pages',
                'menu_title'    => 'Pages',
                'menu_slug'     => 'wdd-pages-options',
                'capability'    => 'edit_posts',
                'parent_slug'   => 'wdd-global-options',
                'redirect'      => false
            )
        );

        acf_add_options_sub_page(
            array(
                'page_title'    => 'Form Settings',
                'menu_title'    => 'Form Settings',
                'menu_slug'     => 'wdd-forms-options',
                'capability'    => 'edit_posts',
                'parent_slug'   => 'edit.php?post_type=form_entry',
                'redirect'      => false
            )
        );
    }

    // Hide Menu Items in Admin Menu that we don't want the client seeing
    public function removeAdminMenuItems()
    {
        // Top Level Pages
        remove_menu_page('edit.php');  //Posts
        remove_menu_page('tools.php');  //Tools
        remove_menu_page('edit-comments.php'); //Comments
        remove_menu_page('link-manager.php'); // Links
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('edit.php?post_type=af_form'); // ACF Forms
        //remove_menu_page('edit.php?post_type=acf-field-group'); //Custom fields

        // Submenu Pages
        remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page'); // Add new
        remove_submenu_page('upload.php', 'media-new.php'); // Add new
        remove_submenu_page('edit.php?post_type=form_entry', 'post-new.php?post_type=form_entry'); // Add new
    }

    // Add Menu Items in Admin Menu
    public function addAdminMenuItems()
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

    public function changeAdminMenuParent()
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
    }

    // Add Separators in Admin Menu
    public function addAdminMenuSeparators()
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
    public function customMenuOrder($menu_ord)
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
    // public function customAdminPageContent()
    // {
    //     esc_html_e('Admin Page Test', 'wdd');
    // }

    // Custom Logo on WP Admin Bar
    public function customDashboardLogo($wp_admin_bar)
    {
        $title = get_bloginfo('name');
        $wp_admin_bar->add_node(
            array(
                'id'    => 'alt-site-name',
                'title' => '<img alt="logo" src="' . get_bloginfo('template_directory') . '/library/admin/logo.svg" style="height: 22px;margin: 3px 5px;float: left;"/>' . $title,
                'href'  => (is_admin() || !current_user_can('read')) ? home_url('/') : admin_url(),
            )
        );
    }

    // Custom Backend Footer
    public function customFooter()
    {
        _e('<img alt="logo" src="' . get_bloginfo('template_directory') . '/library/admin/wild-dog-red.png" style="height: 25px; padding: 3px 0px";/>
		</br>
		<span id="footer-thankyou">Developed by <a href="https://wilddogdesign.co.uk/">Wild Dog Design</a></span>', 'core');
    }

    // Customise the admin menu style
    public function customMenuStyles()
    {
        echo "
        <style>

            #adminmenu .wp-submenu {
                left: 220px;
            }

            #adminmenuback,
            #adminmenuwrap,
            #adminmenu,
            #adminmenu .wp-submenu,
            #adminmenu .wp-not-current-submenu .wp-submenu {
                width: 230px;
            }

            #wpcontent,
            #wpfooter {
                margin-left: 230px;
            }

            #adminmenu li.wp-menu-separator {
                height: 1px;
                padding: 0;
                margin: 6px 10px;
                cursor: inherit;
                background: #313131;
            }

        </style>";
    }
}

new StarterSiteAdminMenu();