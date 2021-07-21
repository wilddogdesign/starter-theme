<?php

require_once('getPostIDs.php');
require_once('getTermIDs.php');

/**
 * Generate an array of the sitemap containing items made of a title and an array of items for each post
 *
 * @return array
 */
function generateSitemap()
{
    $sitemap = [];

    $globalSitemapPostTypes = get_field('global__sitemap_post_types', 'options');
    if (is_array($globalSitemapPostTypes)) {
        foreach ($globalSitemapPostTypes as $postType) {
            // Add PostType
            if (is_array($postType)) {
                $taxonomy = ($postType['value'] == 'news-article-category') || ($postType['value'] == 'publication-category') ? true : false;
                if ($taxonomy) {
                    $pages = getTermIDs($postType['value']);
                    $sitemap[] = addTaxonomyToSitemap($postType['label'], $postType['value'], $pages);
                } else {
                    $pages = getPostIDs($postType['value']);
                    $sitemap[] = addPostTypeToSitemap($postType['label'], $pages, $postType['value']);
                }
            } else {
                $taxonomy = ($postType == 'news-article-category') || ($postType == 'publication-category') ? true : false;
                if ($taxonomy) {
                    $pages = getTermIDs($postType);
                    $postTypeTitle = ucfirst(str_replace('-', ' ', $postType)) . 's';
                    $sitemap[] = addTaxonomyToSitemap($postTypeTitle, $postType, $pages);
                } else {
                    $pages = getPostIDs($postType);
                    $postTypeTitle = ucfirst(str_replace('_', ' ', $postType)) . 's';
                    $sitemap[] = addPostTypeToSitemap($postTypeTitle, $pages);
                }
            }
        }
    }

    return $sitemap;
}

/**
 * Return an array of post IDs of a post type into a formatted array for the sitemap array
 *
 * @param string $title
 * @param array $posts
 * @param string $postType (optional)
 * @return void
 */
function addPostTypeToSitemap($title = '', $posts, $postType = '')
{
    $formattedPosts = [];
    $children = [];
    // First add the top-level items
    if (is_array($posts)) {
        foreach ($posts as $postID) {
            if (get_field('exclude_from_sitemap', $postID) !== true) {
                // Check its not the sitemap itself!
                $pageTemplate = esc_html(get_page_template_slug($postID));
                if ($pageTemplate !== 'template-sitemap.php') {
                    // Check if parent
                    $parentID = wp_get_post_parent_id($postID);
                    if ($parentID && $parentID !== 0) {
                        $children[] = $postID;
                    } else {
                        $formattedPosts[$postID] = [
                            'link'  => get_permalink($postID),
                            'title' => get_the_title($postID),
                            'items' => []
                        ];
                    }
                }
            }
        }
    }

    // Then add the second-level items
    $grandchildren = [];
    if (is_array($children)) {
        foreach ($children as $postID) {
            if (get_field('exclude_from_sitemap', $postID) !== true) {
                // Check its not the sitemap itself!
                $pageTemplate = esc_html(get_page_template_slug($postID));
                if ($pageTemplate !== 'template-sitemap.php') {
                    // Check if parent
                    $parentID = wp_get_post_parent_id($postID);
                    if (isset($formattedPosts[$parentID])) {
                        $formattedPosts[$parentID]['items'][$postID] = [
                            'link'  => get_permalink($postID),
                            'title' => get_the_title($postID)
                        ];
                    } else {
                        $grandchildren[] = $postID;
                    }
                }
            }
        }
    }

    // Then add the third-level items
    $greatgrandchildren = [];
    if (is_array($grandchildren)) {
        foreach ($grandchildren as $postID) {
            if (get_field('exclude_from_sitemap', $postID) !== true) {
                // Check its not the sitemap itself!
                $pageTemplate = esc_html(get_page_template_slug($postID));
                if ($pageTemplate !== 'template-sitemap.php') {
                    // Check if parent
                    $parentID = wp_get_post_parent_id($postID);
                    $grandparentID = wp_get_post_parent_id($parentID);
                    if (isset($formattedPosts[$grandparentID])) {
                        $formattedPosts[$grandparentID]['items'][$parentID]['items'][$postID] = [
                            'link'  => get_permalink($postID),
                            'title' => get_the_title($postID)
                        ];
                    } else {
                        $greatgrandchildren[] = $postID;
                    }
                }
            }
        }
    }

    // Then add the fouth-level items
    if (is_array($greatgrandchildren)) {
        foreach ($greatgrandchildren as $postID) {
            if (get_field('exclude_from_sitemap', $postID) !== true) {
                // Check its not the sitemap itself!
                $pageTemplate = esc_html(get_page_template_slug($postID));
                if ($pageTemplate !== 'template-sitemap.php') {
                    // Check if parent
                    $parentID = wp_get_post_parent_id($postID);
                    $grandparentID = wp_get_post_parent_id($parentID);
                    $greatGrandparentID = wp_get_post_parent_id($grandparentID);
                    if (isset($formattedPosts[$greatGrandparentID])) {
                        $formattedPosts[$greatGrandparentID]['items'][$grandparentID]['items'][$parentID]['items'][$postID] = [
                            'link'  => get_permalink($postID),
                            'title' => get_the_title($postID)
                        ];
                    }
                }
            }
        }
    }

    return [
        'title' => $title,
        'items' => $formattedPosts
    ];
}


/**
 * Return an array of term IDs of a taxonomy into a formatted array for the sitemap array
 *
 * @param string $title
 * @param string $taxonomy
 * @param array $terms
 * @return void
 */
function addTaxonomyToSitemap($title = '', $taxonomy = '', $terms)
{
    $formattedTerms = [];
    if (is_array($terms)) {
        foreach ($terms as $termID) {
            $term = get_term($termID, $taxonomy);
            if (is_object($term) && (get_field('exclude_from_sitemap', $taxonomy . '_' . $term->term_id) != true)) {
                $formattedTerms[] = [
                    'link'  => get_term_link($term->term_id),
                    'title' => $term->name
                ];
            }
        }
    }

    return [
        'title' => $title,
        'items' => $formattedTerms
    ];
}
