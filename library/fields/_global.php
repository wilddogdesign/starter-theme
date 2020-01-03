<?php
if (function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title' => 'Global fields',
    'menu_title' => 'Globals',
    'menu_slug' => 'global-fields',
    'capability' => 'edit_posts',
    'redirect' => false,
  ));

  if (function_exists('acf_add_local_field_group')) {
    
    $prefix = "contact_";
    acf_add_local_field_group(array(
      'key' => $prefix.'metabox',
      'title' => 'Contact information and footer',
      'fields' => array(
        //email
        array(
          'key' => 'field'.$prefix.'email',
          'label' => 'Email',
          'name' => $prefix.'email',
          'type' => 'text',
          'instructions' => '',
          'placeholder' => '',
          'default_value' => '',
          'wrapper' => array(
            'width' => '50',
          ),
        ),
        //phone number
        array(
          'key' => 'field'.$prefix.'phone',
          'label' => 'Phone number',
          'name' => $prefix.'phone',
          'type' => 'text',
          'instructions' => '',
          'placeholder' => '',
          'default_value' => '',
          'wrapper' => array(
            'width' => '50',
          ),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'global-fields',
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
    
    $prefix = "recaptcha_";
    acf_add_local_field_group(array(
      'key' => $prefix.'metabox',
      'title' => 'Recaptcha keys',
      'fields' => array(
        array(
          'key' => 'key_'.$prefix.'key',
          'label' => 'Recaptcha Key',
          'name' => $prefix.'key',
          'type' => 'text',
          'instructions' => '',
          'required' => 1,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'maxlength' => '',
        ),
        array(
          'key' => 'key_'.$prefix.'secret',
          'label' => 'Recaptcha Secret Key',
          'name' => $prefix.'secret',
          'type' => 'text',
          'instructions' => '',
          'required' => 1,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'maxlength' => '',
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'global-fields',
          ),
        ),
      ),
      'menu_order' => 2,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => 1,
      'description' => '',
    ));
    
    $prefix = "social_";
    acf_add_local_field_group(array(
      'key' => 'social_metabox',
      'title' => 'Social Media',
      'fields' => array(
        //facebook
        /**array(
          'key' => 'field_'.$prefix.'facebook',
          'label' => 'Facebook',
          'name' => $prefix.'facebook',
          'type' => 'text',
          'instructions' => '',
          'placeholder' => '',
          'default_value' => '',
          'wrapper' => array(
            'width' => '50',
          ),
        ),**/
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'global-fields',
          ),
        ),
      ),
      'menu_order' => 3,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => 1,
      'description' => '',
    ));
  }
}
