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
        if (WP_ENV == 'development') {
            $this->devHelpers();
        }

        if (WP_ENV == 'staging') {
            require_once('library/helpers/dev/alerts.php');
        }

        $this->criticalFiles();
        $this->criticalActions();

        add_action('init', array($this, 'criticalLibraries'));
        add_action('init', array($this, 'registerRoutes'));

        add_action('after_setup_theme', array($this, 'themeSupport'));
        add_action('after_setup_theme', array($this, 'registerMenus'));

        if (is_admin()) {
            // Determines whether the current request is for an administrative interface page.
            // Admin only Functions
            require_once('admin/functions.php');
        } else {
            // Non-Admin only Functions
            add_filter('timber/context', array($this, 'addToContext'));
            add_filter('timber/twig', array($this, 'addToTwig'));
            add_action('init', array($this, 'loadScripts'));

            if (is_user_logged_in()) {
                require_once('admin/adminBar.php');
            }
        }

        // CLI scripts
        if (defined('WP_CLI') && WP_CLI) {
            $this->loadCommands();
        }

        parent::__construct();
    }

    // Needed on both the front and backend
    public function criticalFiles()
    {
        require_once('library/prioritisePaginationToSlug.php');
    }

    // Needed on both the front and backend
    public function criticalActions()
    {
        // add_action('init', 'sampleFunction');
    }

    /** This is where you can register custom post types and taxonomies. */
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
    public function themeSupport()
    {
        require_once('library/addThemeSupport.php');
    }

    // Needed on both the front and backend
    public function registerMenus()
    {
        register_nav_menus(array(
            'primary_menu'          => __('Main Menu', 'wdd'),
            'footer_menu'           => __('Footer Menu', 'wdd'),
        ));
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function addToContext($context)
    {
        $context['site'] = $this;

        require_once('library/twig-context.php');

        return $context;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function addToTwig($twig)
    {
        require_once('library/twig-extensions.php');

        return $twig;
    }

    public function loadScripts()
    {
        require_once('library/scripts.php');
    }

    public function devHelpers()
    {
        require_once('library/helpers/dev/dd.php');
        require_once('library/helpers/dev/jd.php');

        // Use https://mailtrap.io/ for email testing
        add_action('phpmailer_init', function ($phpmailer) {
            $phpmailer->isSMTP();
            $phpmailer->Host     = 'smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port     = 2525;
            $phpmailer->Username = getenv('MAILTRAP_USERNAME');
            $phpmailer->Password = getenv('MAILTRAP_PASSWORD');
        }, 999);
    }
    public function loadCommands()
    {
        require_once('library/cli/ACFJSONCommand.php');
        require_once('library/cli/getFilename.php');

        WP_CLI::add_command('acf-json sync', ['WP_CLI\ACFJSONCommand', 'sync']);
        WP_CLI::add_command('filename', 'WP_CLI\getFilename');
    }
}

new StarterSite();
