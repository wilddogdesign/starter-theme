<?php

require_once('addCustomUrl.php');

/**
 * Exclude a post type from XML sitemaps.
 *
 * @param boolean $excluded  Whether the post type is excluded by default.
 * @param string  $post_type The post type to exclude.
 *
 * @return bool Whether or not a given post type should be excluded.
 */
function excludePostTypeFromSitemap($excluded, $post_type)
{
    return $post_type === 'post';
}

add_filter('wpseo_sitemap_exclude_post_type', 'excludePostTypeFromSitemap', 10, 2);

/**
 * https://www.benmarshall.me/custom-wordpress-sitemap/
 */

// require_once('generateCustomSitemap.php');

/**
 * Register the custom sitemaps.
 *
 * @link https://developer.yoast.com/features/xml-sitemaps/api/#add-a-custom-post-type
 *
 * @return void
 */
// function registerCustomSitemaps()
// {
//     global $wpseo_sitemaps;

//     if (isset($wpseo_sitemaps) && !empty($wpseo_sitemaps)) {
//         $wpseo_sitemaps->register_sitemap('yourplugin', 'generateCustomSitemap');
//     }
// }
// add_action('init', 'registerCustomSitemaps');

/**
 * Add the custom sitemaps to the sitemap index.
 *
 * @link https://developer.yoast.com/features/xml-sitemaps/api/#adding-content
 *
 * @return string XML sitemap index.
 */
// function addCustomSitemaps($sitemap_index)
// {
//     $sitemap_url    = home_url('yourplugin-sitemap.xml');
//     $sitemap_date   = date(DATE_W3C);
//     $custom_sitemap = <<<SITEMAP_INDEX_ENTRY
//     <sitemap>
//     <loc>%s</loc>
//     <lastmod>%s</lastmod>
//     </sitemap>
//     SITEMAP_INDEX_ENTRY;
//     $sitemap_index .= sprintf($custom_sitemap, $sitemap_url, $sitemap_date);

//     return $sitemap_index;
// }

// add_filter('wpseo_sitemap_index', 'addCustomSitemaps');
