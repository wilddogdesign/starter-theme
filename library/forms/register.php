<?php
//register forms
function register_forms() {
  $forms = [
    /**[
      'page_id'  => get_field('field_form_name','option')->ID,
      'form_key' => 'form_name',
      'title'    => 'Form name',
    ],**/
  ];
  
  foreach ($forms as $form) {
    if ((array_key_exists('page_id', $form) && $form['page_id']) || (array_key_exists('global', $form) && $form['global'])) {
      $emails = [];
      // $emails = get_field('field_form_emails', $form['page_id']);
      $emails = array_key_exists('global', $form) && $form['global'] ?
        get_fields('options')['global_' . $form['form_key'] . '_emails'] : get_field('field_form_emails', $form['page_id']);
      
      $form_args = array(
        'title' => $form['title'],
        'key' => $form['form_key'],
        'display' => array(
          'description' => '',
          'success_message' => '',
        ),
        'create_entries' => true,
        'restrict_entries' => false,
        'entries_limit' => 0,
        'entries_restriction_message' => '',
        'emails' => $emails,
      );
      
      af_register_form($form_args);
    }
  }
}

add_action('af/register_forms', 'register_forms');
