<?php

if (!defined('ABSPATH')) {
    exit;
}

/** This is where you add some context
 *
 * @param string $context context['this'] Being the Twig's {{ this }}.
 */

class TwigAddToContext
{
    public static function addToContext($context)
    {
        global $post;

        // Global Options (Avoid when possible)
        // $context['options'] = get_fields('options');

        // Menus
        $context['mainMenu'] = new TimberMenu('primary_menu');
        $context['footerMenu'] = new TimberMenu('footer_menu');

        // Page Information
        $context['current_url'] = $_SERVER['REQUEST_URI'];
        // $context['current_template'] = get_page_template_slug();
        // $context['server_name'] = $_SERVER['SERVER_NAME'];
        // $context['is_live'] = WP_ENV === 'production' && strpos(get_home_url(), 'wilburystratton') > -1;

        // Post Content
        $context['excerpt'] = get_the_excerpt();

        // ReCaptcha
        $context['reCaptchaKey'] = get_field('google__recaptcha_key', 'options') ? get_field('google__recaptcha_key', 'options') : false;
        $context['reCaptchaSecret'] = get_field('google__recaptcha_secret', 'options') ? get_field('google__recaptcha_secret', 'options') : false;

        // Google Analytics
        $context['googleAnalyticsAPIKey'] = get_field('google__analytics_key', 'options') ? get_field('google__analytics_key', 'options') : false;

        // Global Pages
        $context['globalContactPage'] = get_field('global_pages__contact', 'options') ? new TimberPost(get_field('global_pages__contact', 'options')) : false;

        // Contact Information
        $context['globalPhoneNumber'] = get_field('global_contact__phone', 'options');
        $context['globalEmail'] = get_field('global_contact__email', 'options');
        $context['globalAddress'] = get_field('global_contact__address', 'options');

        // Socials
        $context['socials']['facebook'] = get_field('global_social__facebook', 'options');
        $context['socials']['twitter'] = get_field('global_social__twitter', 'options');
        $context['socials']['instagram'] = get_field('global_social__instagram', 'options');
        $context['socials']['linkedin'] = get_field('global_social__linkedin', 'options');

        // CSS
        $fileLocation = glob('app/themes/bedrock-theme/static/css/main-*.css')[0];
        $cssFile = substr($fileLocation, strrpos($fileLocation, '/') + 1);
        //$serviceLocation = glob('app/themes/bedrock-theme/static/js/service-worker-*.js')[0];
        //$context['serviceworker'] = substr($serviceLocation, strrpos($serviceLocation, '/') + 1);

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
}
