<?php

// Add the columns to forms entries admin interface from all the forms
// If some forms don't share the field names, nothing will be displayed
// for that particular entry

add_filter('manage_af_entry_posts_columns', 'af_entry_table_head');
function af_entry_table_head($columns) {
    $columns['name'] = 'Name';
    //$columns['last_name'] = 'Surname';
    $columns['email'] = 'Email';
    // Move date field to the end
    $new_columns = array_filter($columns, function($column) {
        return $column !== 'date';
    }, ARRAY_FILTER_USE_KEY);
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}

add_action('manage_af_entry_posts_custom_column', 'af_entry_table_content', 10, 2);
function af_entry_table_content($column_name, $post_id) {
    switch ($column_name) {
        case 'name':
            $name = get_field('name', $post_id);
            $firstname = get_field('first_name', $post_id);
            $surname = get_field('last_name', $post_id);
            echo $name.$firstname.' '.$surname ? $name.$firstname.' '.$surname : '';
            break;
        case 'last_name':
            $surname = get_field('last_name', $post_id);
            echo $surname ? $surname : '';
            break;
        case 'email':
            $email = get_field('email', $post_id);
            echo $email ? $email : '';
            break;
    }
}

/**add_filter('manage_edit-af_entry_sortable_columns', 'custom_sortable_forms_columns');
function custom_sortable_forms_columns($columns) {
    $columns['name'] = 'Name';
    $columns['email'] = 'Email';
    return $columns;
}**/
