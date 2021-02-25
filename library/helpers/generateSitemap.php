<?php

require_once('getPostIDs.php');

/**
 * Generate an array of the sitemap containing items made of a title and an array of items for each post
 *
 * @return array
 */
function generateSitemap()
{
    $sitemap = [];

    $globalSitemapPostTypes = get_field('global__sitemap_post_types', 'global-options');
    if (is_array($globalSitemapPostTypes)) {
        foreach ($globalSitemapPostTypes as $postType) {
            // Add PostType
            $pages = getPostIDs($postType);
            $postTypeTitle = ucwords(str_replace('_', ' ', $postType)) . 's';
            $sitemap[] = addPostTypeToSitemap($postTypeTitle, $pages);
        }
    }

    return $sitemap;
}

/**
 * Return an array of post IDs of a post type into a formatted array for the sitemap array
 *
 * @param string $title
 * @param array $posts
 * @return void
 */
function addPostTypeToSitemap($title = '', $posts)
{
    $formattedPosts = [];
    if (is_array($posts)) {
        foreach ($posts as $postID) {
            if (get_field('exclude_from_sitemap', $postID) !== true) {
                // Check its not the sitemap itself!
                $pageTemplate = esc_html(get_page_template_slug($postID));
                if ($pageTemplate !== 'template-sitemap.php') {
                    // Check if parent
                    $parentID = wp_get_post_parent_id($postID);
                    if ($parentID && $parentID !== 0) {
                        $formattedPosts[$parentID]['items'][] = [
                            'link'  => get_permalink($postID),
                            'title' => get_the_title($postID)
                        ];
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

    return [
        'title' => $title,
        'items' => $formattedPosts
    ];
}


// NOT USED. NOT WORTH DELETING AS A GOOD BASE IF NEEDED IN THE FUTURE
// /**
//  * Return an array of term IDs of a taxonomy into a formatted array for the sitemap array
//  *
//  * @param string $title
//  * @param string $taxonomy
//  * @param array $terms
//  * @return void
//  */
// function addTaxonomyToSitemap($title = '', $taxonomy = '', $terms)
// {
//     $formattedTerms = [];
//     if (is_array($terms)) {
//         foreach ($terms as $termID) {
//             $term = get_term($termID, $taxonomy);
//             // if (get_field('include_in_sitemap', $taxonomy . '_' . $termID)) {
//             $formattedTerms[] = [
//                 'link'  => get_term_link($termID),
//                 'title' => $term->name
//             ];
//             // }
//         }
//     }

//     return [
//         'title' => $title,
//         'items' => $formattedTerms
//     ];
// }
