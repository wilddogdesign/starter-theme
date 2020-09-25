<?php
if (!defined('ABSPATH')) {
    exit;
}

class AutopRelationships
{

    public function __construct()
    {
        /* Hooks all ACF Load Field Hooks required actions by this class */
        // add_filter('acf/fields/relationship/query/name=autop__destination_country_selector', array($this, 'countries'), 10, 3);
    }

    // public static function countries($args, $field, $post_id)
    // {
    //     $args['meta_query'] = array(
    //         array(
    //             'key' => 'destination_type',
    //             'value' => 'country'
    //         )
    //     );
    //     return $args;
    // }
}

new AutopRelationships();
