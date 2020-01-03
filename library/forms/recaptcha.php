<?php

/**
 * Check if the recpatcha is valid.
 *
 * @return boolean
 */
function isRecaptchaValid()
{
    $captcha = false;

    if (isset($_POST['g-recaptcha-response'])) {
        $captcha = $_POST['g-recaptcha-response'];
    }

    if ($captcha) {
        $secret = get_fields('options')['recaptcha_secret'];

        // calling google recaptcha api.
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

        $res = json_decode($response);

        if ($res->success) {
            return true;
        }
    }

    return false;
}

/**
 * Validate the form for recaptcha.
 *
 * @param [type] $form
 * @param [type] $args
 * @return void
 */
function validate_form($form, $args)
{
    if (!isRecaptchaValid()) {
        $keys = array_keys($_POST['acf']);
        $first_field = reset($keys);
        af_add_error($first_field, 'There was an error submitting the form. Please go back and try again.');
    }
}

add_action('af/form/validate/key=form_name', 'validate_form', 10, 2);
