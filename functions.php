<?php

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

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

// Load libraries and scripts
require_once('library/scripts.php');
require_library_dir('fields');
require_library_dir('helpers');

// custom forms
require_once('library/forms/init-headless.php');
//require_once('library/forms/fields/form.php');
require_once('library/forms/register.php');
require_once('library/forms/recaptcha.php');
require_once('library/forms/admin-enhancements.php');
do_action('af/register_forms');

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
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        parent::__construct();
    }
    /** This is where you can register custom post types. */
    public function register_post_types()
    {
        require_library_dir('post-types');
    }

    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {
        require_library_dir('taxonomies');
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['foo']   = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        // You can do $context['menu'] = new Timber\Menu('hyphenated-menu-name-from-admin');
        $context['menu']  = new Timber\Menu();
        $context['site']  = $this;

        /**
         * CSS
         * */
        $fileLocation = glob('app/themes/bedrock-theme/static/css/main-*.css')[0];
        $cssFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);

        // Get css
        $cssFiles = glob('app/themes/bedrock-theme/static/css/main-*.css');

        // remove critical as it also has main- in it's name now
        foreach ($cssFiles as $key => $filename) {
            if (strpos($filename, "critical")) {
                $critical_file_path = $cssFiles[$key];
                unset($cssFiles[$key]);
            }
        }

        // now get the location, it's the only one left
        $fileLocation = array_values($cssFiles)[0];
        $cssFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);
        $context['static'] = get_stylesheet_directory_uri() . '/static/';
        $context['css'] = $context['static'] . 'css/' . $cssFile;
        $context['criticalCss'] = str_replace('../', get_stylesheet_directory_uri() . '/static/', file_get_contents($critical_file_path));

        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
        add_theme_support('title-tag');

        /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
        add_theme_support('post-thumbnails');

        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());
        $twig->addFilter(new Twig\TwigFilter('myfoo', array($this, 'myfoo')));
      
        //add a class to paragraphs |pclass('class')
        $twig->addFilter('pclass', new Twig_SimpleFilter('pclass', function($string, $class) {
            return str_replace('<p>', '<p class="'.$class.'">', $string);
        }));
        
        return $twig;
    }
}

// Remove menu items we don't want the client seeing
function remove_menus()
{
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    remove_menu_page('themes.php'); // Appearance
    remove_menu_page('tools.php'); // Tools
    // You might want the following at first
    // remove_menu_page('edit.php?post_type=af_form'); // Forms
    // remove_menu_page('edit.php?post_type=acf-field-group'); //Custom fields
}

add_action('admin_menu', 'remove_menus');

// Add menus back in
function add_menu_link()
{
    add_menu_page(
        __('Menus', 'textdomain'),
        'Menus',
        'manage_options',
        'nav-menus.php',
        '',
        'dashicons-networking',
        41
    );
}

add_action('admin_menu', 'add_menu_link');

// Uncomment this if you're using Advanced Forms
/**function add_entries_link()
{
  add_menu_page(
    __('Menus', 'textdomain'),
    'Form entries',
    'manage_options',
    'edit.php?post_type=af_entry',
    '',
    'dashicons-email-alt',
    80
  );
}

add_action('admin_menu', 'add_entries_link');**/

// Uncomment this if you're using google maps and need them to work in the back-end using the key set in globals
/**function my_acf_google_map_api($api)
{
  $api['key'] = get_field('field_map_key', 'option');
  return $api;
}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');**/

new StarterSite();
