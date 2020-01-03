<?php

/**
 * Handles fetching, validation, and saving of forms
 * Reworked from Advanced Form core
 *
 * @since 1.0.0
 *
 */
global $af_headless_forms;

class AF_Headless_Forms
{
  function __construct()
  {
    // Remove the main AF_Forms init action
    remove_class_action('init', 'AF_Core_Forms', 'pre_form', 10);
    add_action('init', array($this, 'pre_form'), 10, 0);

    add_filter('wp_create_file_in_uploads', 'grab_upload_id', 10, 2);

    function grab_upload_id($file, $id)
    {
      if (isset($_POST['acf'])) {
        $_POST['acf_extra']['file'] = $id;
      }
    }
  }

  /**
   * Handles submissions and enqueue of neccessary scripts
   * Relies on default ACF validations
   *
   * @since 1.0.0
   *
   */
  function pre_form()
  {

    if (isset($_POST['af_form']) && !isset($_POST['action'])) {

      $form_key_or_id = $_POST['af_form'];

      $form = af_get_form($form_key_or_id);

      // Validate the posted data, this validation has already been performed once over AJAX
      if ($form && acf_validate_save_post(true)) {

        setcookie($form['key'], true, time() + 3600, COOKIEPATH, COOKIE_DOMAIN);

        // Increase the form submissions counter
        if ($form['post_id']) {
          $submissions = get_post_meta($form['post_id'], 'form_num_of_submissions', true);
          $submissions = $submissions ? $submissions + 1 : 1;
          update_post_meta($form['post_id'], 'form_num_of_submissions', $submissions);
        }


        // Retrieve the args used to display the form
        $args = json_decode(base64_decode($_POST['af_form_args']), true);


        /**
         * Upload all files in $_FILES using ACFs helper function. Required for basic uploads to work painlessly.
         * TODO: Move to af_save_field() to avoid saving all files?
         *
         * @since 1.3.1
         *
         */
        if (isset($_FILES['acf'])) {
          acf_upload_files();
        }


        // Retrieve all form fields and their values
        $fields = array();

        if (isset($_POST['acf'])) {

          foreach ($_POST['acf'] as $k => $value) {

            $field = acf_get_field($k);

            $field['_input'] = $value;
            $field['value'] = acf_format_value($value, 0, $field);

            $fields[] = $field;
          }

          if (isset($_POST['acf'])) {
            foreach ($_POST['acf_extra'] as $k => $value) {

              $field = acf_get_field($k);

              $field['_input'] = $value;
              $field['value'] = acf_format_value($value, 0, $field);

              $fields[] = $field;
            }
          }
        }


        // Save submission data to the global AF object
        AF()->submission = array(
          'form' => $form,
          'args' => $args,
          'fields' => $fields,
        );


        do_action('af/form/submission', $form, $fields, $args);
        do_action('af/form/submission/id=' . $form['post_id'], $form, $fields, $args);
        do_action('af/form/submission/key=' . $form['key'], $form, $fields, $args);

        // Redirect to different URL if redirect argument has been passed
        if ($args['redirect'] && '' != $args['redirect']) {

          wp_redirect($args['redirect']);

          exit;
        } else {

          wp_redirect(acf_get_current_url());

          exit;
        }
      }
    }
  }

  /**
   * Returns the rendered form as array of fields specified by ID
   *
   * @since 1.0.0
   *
   */
  function get_rendered_form_array($form_id_or_key, $args)
  {

    $form = af_get_form($form_id_or_key);

    if (!$form) {
      return;
    }

    $form_to_return = [];

    // Allow the form to be modified before rendering form
    $form = apply_filters('af/form/before_render', $form, $args);
    $form = apply_filters('af/form/before_render/id=' . $form['post_id'], $form, $args);
    $form = apply_filters('af/form/before_render/key=' . $form['key'], $form, $args);

    $args = wp_parse_args($args, array(
      'display_title'       => false,
      'display_description' => false,
      'id'                  => $form['key'],
      'values'              => array(),
      'submit_text'         => __('Submit', 'advanced-forms'),
      'redirect'            => false,
      'target'              => acf_get_current_url(),
      'echo'                => true,
      'exclude_fields'      => array(),
      'uploader'            => 'wp',
      'filter_mode'         => false,
    ));

    // Allow the arguments to be modified before rendering form
    $args = apply_filters('af/form/args', $args, $form);
    $args = apply_filters('af/form/args/id=' . $form['post_id'], $args, $form);
    $args = apply_filters('af/form/args/key=' . $form['key'], $args, $form);


    // Increase the form view counter
    if ($form['post_id']) {
      $views = get_post_meta($form['post_id'], 'form_num_of_views', true);
      $views = $views ? $views + 1 : 1;
      update_post_meta($form['post_id'], 'form_num_of_views', $views);
    }


    // Form element
    $form_attributes = array(
      // 'class'    => 'af-form acf-form',
      'method'  => 'POST',
      'action'  => $args['target'],
      'id'    => $args['id'],
    );

    $form_attributes = apply_filters('af/form/attributes', $form_attributes, $form, $args);
    $form_attributes = apply_filters('af/form/attributes/id=' . $form['post_id'], $form_attributes, $form, $args);
    $form_attributes = apply_filters('af/form/attributes/key=' . $form['key'], $form_attributes, $form, $args);

    $form_to_return['attributes'] = $form_attributes;

    // Display title
    if ($args['display_title']) {
      $form_to_return['title'] = $form['title'];
    }


    // Display description
    if ($args['display_description']) {
      $form_to_return['description'] = $form['display']['description'];
    }


    /**
     * Check if form should be restricted and not displayed.
     * Filter will return false if no restriction is applied otherwise it will return a string to display.
     */
    $restriction = false;
    $restriction = apply_filters('af/form/restriction', $restriction, $form, $args);
    $restriction = apply_filters('af/form/restriction/id=' . $form['post_id'], $restriction, $form, $args);
    $restriction = apply_filters('af/form/restriction/key=' . $form['key'], $restriction, $form, $args);

    // Display success message, restriction message, or fields
    if (af_has_submission() && !$args['filter_mode']) {
      $form_to_return['message']['status'] = 'success';
      $form_to_return['message']['text'] = af_resolve_field_includes($form['display']['success_message']);
    } elseif ($restriction) {
      $form_to_return['message']['status'] = 'restricted';
      $form_to_return['message']['text'] = $restriction;
    } else {

      // Set ACF uploader type setting
      acf_update_setting('uploader', $args['uploader']);

      // Get field groups for the form and display their fields
      $field_groups = af_get_form_field_groups($form['key']);

      if (isset($_COOKIE[$args['id']])) {
        $form_to_return['submitted'] = !!$_COOKIE[$args['id']];
        setcookie($args['id'], '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
      } else {
        $form_to_return['submitted'] = false;
      }

      $form_to_return['hidden_fields'] = [];
      $form_to_return['hidden_fields']['af_form'] = ['name' => 'af_form', 'type' => 'hidden', 'value' => $form['key']];
      $form_to_return['hidden_fields']['af_form_args'] = ['name' => 'af_form_args', 'type' => 'hidden', 'value' => base64_encode(json_encode($args))];
      $form_to_return['hidden_fields']['_acf_from'] = ['name' => '_acf_from', 'type' => 'hidden', 'value' => base64_encode(json_encode($args))];

      $form_to_return['fields'] = [];
      foreach ($field_groups as $field_group) {

        // Get all fields for field group
        $fields = acf_get_fields($field_group);

        foreach ($fields as $field) {

          // Skip field if it is in the exluded fields argument
          if (isset($args['exclude_fields']) && is_array($args['exclude_fields'])) {

            if (in_array($field['key'], $args['exclude_fields']) || in_array($field['name'], $args['exclude_fields'])) {
              continue;
            }
          }

          // Include default value
          if (empty($field['value']) && isset($field['default_value'])) {
            $field['value'] = $field['default_value'];
          }


          // Include pre-fill values (either through args or filter)
          if (isset($args['values'][$field['name']])) {
            $field['value'] = $args['values'][$field['name']];
          }

          if (isset($args['values'][$field['key']])) {
            $field['value'] = $args['values'][$field['key']];
          }

          $field['value'] = apply_filters('af/field/prefill_value', $field['value'], $field, $form, $args);
          $field['value'] = apply_filters('af/field/prefill_value/name=' . $field['name'], $field['value'], $field, $form, $args);
          $field['value'] = apply_filters('af/field/prefill_value/key=' . $field['key'], $field['value'], $field, $form, $args);


          // Include any previously submitted value
          if (isset($_POST['acf'][$field['key']])) {

            $field['value'] = $_POST['acf'][$field['key']];
          }

          $field_to_return = [];
          $field_to_return['label'] = $field['label'];
          $field_to_return['name'] = $field['name'];
          $field_to_return['required'] = $field['required'];
          if ('' != $field['instructions']) {
            $field_to_return['instructions'] = $field['instructions'];
          }
          $field_to_return['type'] = $field['type'];
          if ($field['type'] === 'select' || $field['type'] === 'checkbox') $field_to_return['choices'] = $field['choices'];
          if ($field['type'] === 'textarea') $field_to_return['rows'] = $field['rows'];
          $field_to_return['value'] = $field['value'];
          if (array_key_exists('placeholder', $field)) $field_to_return['placeholder'] = $field['placeholder'];
          $form_to_return['fields'][$field['key']] = $field_to_return;
        }
      }
    }

    return $form_to_return;
  }
}

$af_headless_forms = new AF_Headless_Forms;
