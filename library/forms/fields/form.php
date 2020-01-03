<?php
if (function_exists('acf_add_local_field_group')) {

  $prefix = 'form_';
  acf_add_local_field_group(array(
    'key' => 'form_'.$prefix,
    'title' => 'Form fields',
    'fields' => array(
      // Name
      array(
        'key' => 'field_'.$prefix.'name',
        'label' => 'Full name',
        'name' => 'name',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'hide_admin' => 0,
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'wrapper' => array(
          'width' => '50',
        ),
      ),
      // Email
      array(
        'key' => 'field_'.$prefix.'email',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'hide_admin' => 0,
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'wrapper' => array(
          'width' => '50',
        ),
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'af_form',
          'operator' => '==',
          'value' => 'form_name',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
  ));
}
