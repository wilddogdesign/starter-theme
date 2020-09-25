<?php

/**
 * Load all php files in a given directory
 * @param  string $path
 */
function require_library_dir($path)
{
    array_map(function ($file) {
        require_once($file);
    }, glob(__DIR__ . "/library/{$path}/*.php"));
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function ($template) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}
/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        $this->criticalHelpers();
        $this->criticalActions();

        if (is_admin()) {
            $this->adminHelpers();
            $this->adminActions();
        } elseif (is_user_logged_in()) {
            require_once('library/admin/StarterSiteAdminBar.php');
        }

        if (WP_ENV === 'development') {
            $this->devHelpers();
        }

        parent::__construct();
    }

    // Needed on both the front and backend
    public function criticalHelpers()
    {
        // Include Critical Helpers
        require_once('library/helpers/critical/ThemeSupport.php');
        require_once('library/helpers/critical/RegisterMenus.php');

        // Not Admin Page
        if (!is_admin()) {
            // Include Critical Helpers
            require_once('library/helpers/critical/TwigAddToContext.php');
            require_once('library/helpers/critical/TwigExtensions.php');
        }
    }

    // Needed on both the front and backend
    public function criticalActions()
    {
        add_action('after_setup_theme', array($this, 'criticalThemeSupport'));
        add_action('after_setup_theme', array($this, 'criticalRegisterMenus'));
        add_action('init', array($this, 'criticalLibraries'));
        add_action('init', array($this, 'registerRoutes'));

        // Not Admin Page
        if (!is_admin()) {
            add_action('init', array($this, 'criticalScripts'));
            add_filter('timber/context', array($this, 'addToContext'));
            add_filter('timber/twig', array($this, 'addToTwig'));
        }
    }

    // Needed on both the front and backend
    public function criticalThemeSupport()
    {
        new ThemeSupport();
    }

    // Needed on both the front and backend
    public function criticalRegisterMenus()
    {
        new RegisterMenus();
    }

    // Needed on both the front and backend
    public function criticalLibraries()
    {
        require_library_dir('post-types');
        require_library_dir('taxonomies');
    }

    // Needed on both the front and backend
    public function registerRoutes()
    {
        require_once('library/routes.php');
    }

    // Needed on both the front and backend
    public function criticalScripts()
    {
        require_once('library/scripts.php');
    }

    public function addToContext($context)
    {
        // Site Information
        $context['site'] = $this;
        $context['site_url'] = get_home_url();
        $context = TwigAddToContext::addToContext($context);

        if (isset($_GET["form-status"]) && $_GET["form-status"] == 'error' && !empty($_COOKIE['form_errors'])) {
            $context['form_errors'] = json_decode(stripslashes($_COOKIE['form_errors']));
            $context['old_inputs'] = json_decode(stripslashes($_COOKIE['old_inputs']));
        }

        // TODO
        $liveUrls = [
            'tbc.com',
            'tbc.wilddogdevelopment.com',
        ];

        $context['is_live'] = in_array($_SERVER['HTTP_HOST'], $liveUrls);

        return $context;
    }

    public function addToTwig($twig)
    {
        $twig = TwigExtensions::addToTwig($twig);

        return $twig;
    }

    // Link to Admin JS file
    public function includeJS()
    {
        wp_enqueue_script('js-file', get_template_directory_uri() . '/library/js/admin.js', array(), false, true);
    }

    // Only needed on the backend
    public function adminHelpers()
    {
        // Additional Helpers

        require_library_dir('admin'); // Admin Menu, Admin Bar, Image Control
        require_library_dir('admin/acf-fields'); // Dynamic ACF Fields
        require_library_dir('admin/helpers'); // Admin Helpers
        require_library_dir('admin/pages'); // Admin Pages
    }

    // Only needed on the backend
    public function adminActions()
    {
        add_action('admin_enqueue_scripts', array($this, 'includeJS')); // ACF JSON Script
    }

    public function devHelpers()
    {
        require_once('library/helpers/dd.php');
    }
}

new StarterSite();
