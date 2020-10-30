<?php

if (!defined('ABSPATH')) {
    exit;
}

class BiDirectionalRelationships
{

    public function __construct()
    {
        // Project Specific
        add_filter('acf/update_value/name=relationship__a_b', array($this, 'bidirectionalRelationship'), 10, 3);
    }

    public static function bidirectionalRelationship($value, $post_id, $field)
    {

        $field_name = $field['name'];
        $field_key = $field['key'];
        $global_name = 'is_updating_' . $field_name;

        if (!empty($GLOBALS[$global_name])) {
            return $value; // This prevents an inifinte loop
        }
        $GLOBALS[$global_name] = 1; // set global variable to avoid inifite loop

        if (is_array($value)) {
            foreach ($value as $post_id2) {
                $value2 = get_field($field_name, $post_id2, false); // load existing related posts

                if (empty($value2)) { // allow for selected posts to not contain a value
                    $value2 = array();
                }

                if (in_array($post_id, $value2)) { // bail early if the current $post_id is already found in selected post's $value2
                    continue;
                }

                $value2[] = $post_id; // append the current $post_id to the selected post's 'related_posts' value

                update_field($field_key, $value2, $post_id2); // update the selected post's value (use field's key for performance)
            }
        }

        // find posts which have been removed
        $old_value = get_field($field_name, $post_id, false);

        if (is_array($old_value)) {
            foreach ($old_value as $post_id2) {
                if (is_array($value) && in_array($post_id2, $value)) {
                    continue; // bail early if this value has not been removed
                }

                $value2 = get_field($field_name, $post_id2, false); // load existing related posts

                if (empty($value2)) {
                    continue; // bail early if no value
                }

                $pos = array_search($post_id, $value2); // find the position of $post_id within $value2 so we can remove it
                unset($value2[$pos]); // remove
                update_field($field_key, $value2, $post_id2); // update the un-selected post's value (use field's key for performance)
            }
        }

        // reset global varibale to allow this filter to function as per normal
        $GLOBALS[$global_name] = 0;

        return $value;
    }
}

new BiDirectionalRelationships();
