<?php

// prioritetize pagination over displaying custom post type content
function prioritisePaginationToSlug()
{
    add_rewrite_rule('(.?.+?)/page/?([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&paged=$matches[2]', 'top');
}
