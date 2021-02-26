<?php

function addCustomUrl($url, $date)
{
    $customUrl = <<<SITEMAP_INDEX_ENTRY
        <url>
            <loc>%s</loc>
            <lastmod>%s</lastmod>
        </url>
        SITEMAP_INDEX_ENTRY;

    return sprintf($customUrl, $url, $date);
}

// function addCustomUrl($url, $date, $imageUrl = '', $imageTitle = '', $imageCaption = '')
// {
//     $customUrl = <<<SITEMAP_INDEX_ENTRY
//         <url>
//             <loc>%s</loc>
//             <lastmod>%s</lastmod>
//             <image:image>
//                 <image:loc>%s</image:loc>
//                 <image:title><![CDATA[%s]]></image:title>
//                 <image:caption><![CDATA[%s]]></image:caption>
//             </image:image>
//         </url>
//         SITEMAP_INDEX_ENTRY;

//     return sprintf($customUrl, $url, $date, $imageUrl, $imageTitle, $imageCaption);
// }
