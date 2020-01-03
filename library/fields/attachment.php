<?php
$current_language = apply_filters('wpml_current_language', null);

if (function_exists('acf_add_local_field_group')) {
  //attachment
  $prefix = "attachment_";
  acf_add_local_field_group(
    array(
      'key' => $prefix . 'metabox',
      'title' => 'Hero',
      'fields' => array(
        array(
          'key' => 'field_'.$prefix.'image_position',
          'label' => 'Image position',
          'name' => $prefix.'image_position',
          'type' => 'select',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'hide_admin' => 0,
          'choices' => array(
            'center' => 'Center',
            'left' => 'Left',
            'right' => 'Right',
            'top' => 'Top',
            'bottom' => 'Bottom',
          ),
          'default_value' => array(
            'center',
          ),
          'allow_null' => 1,
          'multiple' => 0,
          'ui' => 0,
          'ajax' => 0,
          'return_format' => 'value',
          'placeholder' => '',
          'wrapper' => array(
            'width' => '',
          ),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'attachment',
            'operator' => '==',
            'value' => 'image',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => array(
        0 => 'the_content',
        1 => 'comments',
      ),
      'active' => 1,
      'description' => '',
    )
  );
}
