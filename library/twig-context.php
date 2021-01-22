<?php

if (!defined('ABSPATH')) {
    exit;
}

/** This is where you add some context
 *
 * $context context['this'] Being the Twig's {{ this }}.
 */

/**
 * MENUS
 */
$context['mainMenu'] = new TimberMenu('primary_menu');
$context['footerMenu'] = new TimberMenu('footer_menu');;

/**
 * SITE VARIABLES
 */
$liveUrls = [
    'www.tbc.co.uk',
    'tbc.co.uk',
    // 'tbc.wilddogdevelopment.com',
];

$context['is_live'] = in_array($_SERVER['HTTP_HOST'], $liveUrls);
$context['is_staging'] = WP_ENV === 'staging';

/**
 * THIRD PARTY API, ETC
 */

// ReCaptcha
$context['reCaptchaKey'] = get_field('google__recaptcha_key', 'options') ?: '';
$context['reCaptchaSecret'] = get_field('google__recaptcha_secret', 'options') ?: '';

// Google analytics
$context['googleAnalyticsAPIKey'] = get_field('google__analytics_key', 'options') ?: false;

// Google Tag Manager
$context['googleTagManagerId'] = get_field('google__tag_manager_id', 'options') ?: '';

// Facebook Pixel ID
$context['facebookPixelID'] = get_field('global__facebook_pixel_ID', 'options') ?: '';

// Google Maps
$context['googleMapsAPIKey'] = get_field('google__maps_api_key', 'options') ?: false;

/**
 * SEO
 */

// TODO - decide whether to use this or Yoast.

// $context['seo_title'] = get_field('seo_title') ?: get_the_title();
// $context['seo_description'] = get_field('seo_description') ?: $context['excerpt'];
// $context['seo_type'] = get_field('seo_type') ?: 'article';
// $context['seo_keywords'] = get_field('seo_keywords') ?: '';
// $context['seo_canonical_url'] = get_field('seo_canonical_url') ?: $_SERVER['REQUEST_URI'];
// $context['seo_hide_from_search'] = $context['is_live'] ? (get_field('seo_hide_from_search') ?: false) : true;
// $context['seo_image'] = get_field('seo_image') ? new Timber\Image(get_field('seo_image')) : null;

/**
 * CSS
 **/

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
