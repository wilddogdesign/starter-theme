<?php

/**
 * Generate a custom sitemap
 *
 * @return void
 */
function generateCustomSitemap()
{
    global $wpseo_sitemaps;

    $urls = [];
    $urls[] = $wpseo_sitemaps->renderer->sitemap_url([
        'mod'    => date('c', strtotime('date_modified')),
        'loc'    => 'https://page.url',
        'images' => [
            [
                'src'   => 'https://image.url',
                'title' => 'Image title',
                'alt'   => 'Image alt',
            ]
        ]
    ]);

    $sitemap_body = <<<SITEMAP_BODY
<urlset
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd"
xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
%s
</urlset>
SITEMAP_BODY;
    $sitemap = sprintf($sitemap_body, implode("\n", $urls));
    $wpseo_sitemaps->set_sitemap($sitemap);
}
