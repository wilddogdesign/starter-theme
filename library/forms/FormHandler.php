<?php

if (!defined('ABSPATH') || $_SERVER['REQUEST_METHOD'] === 'GET') {
    wp_safe_redirect('/');

    exit;
}
global $errors;

class FormHandler
{
    /**
     * The redirect URL.
     *
     * @var [type]
     */
    protected $redirectURL;

    /**
     * Initiate the FormHandler
     */
    public function __construct()
    {
        $this->setRedirectURL();

        $form = isset($_POST['form']) ? $_POST['form'] : false;

        // Check nonce is valid if ($form && isset($_POST['kipling_wpnonce_name']) && wp_verify_nonce($_POST['kipling_wpnonce_name'], 'kipling_wpnonce_action')) {
        if ($form) {

            $response = [];
            // Validate the reCaptcha
            $validRecaptcha = $this->validateReCaptcha();
            if (!$validRecaptcha) {
                $response['errors'] = ['recaptcha' => 'Invalid Recaptcha'];
            }

            if (isset($response['errors'])) {
                // deal with errors
                $this->redirectBackWithError($response['errors']);
            } else {
                switch ($_POST['form']) {
                    case 'contact':
                        // Contact Page Form
                        require_once('ContactFormSubmission.php');
                        $form = new ContactFormSubmission();
                        break;

                    default:
                        wp_safe_redirect('/');

                        exit;
                        break;
                }

                // if successful the form will redirect and not return errors
                $response = $form->submit();

                if ($response['errors']) {
                    // deal with errors
                    $this->redirectBackWithError($response['errors']);
                } else {
                    $formID = isset($_POST['id']) ? $_POST['id'] : '';

                    $params = "?form={$formID}&form-status=success";

                    if (!empty($response['params']['thankYouPageURL'])) {
                        wp_safe_redirect($response['params']['thankYouPageURL']);

                        exit;
                    }

                    if (!empty($response['params'])) {
                        foreach ($response['params'] as $key => $value) {
                            // $value = urlencode($value);
                            $params .= "&{$key}={$value}";
                        }
                    }

                    wp_safe_redirect($this->redirectURL . $params);
                }
            }

            die();
        } else {
            // Form is not valid
            wp_safe_redirect('/');
            exit;
        }
    }

    /**
     * Validate the recaptcha in the form request.
     *
     * @return boolean
     */
    public function validateReCaptcha()
    {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        } else {
            return false;
        }

        $secret = get_field('google__recaptcha_secret', 'options') ?: false;

        // calling google recaptcha api.
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

        $res = json_decode($response);

        return $res->success;
    }

    /**
     * Set the redirect URL.
     *
     * @return void
     */
    private function setRedirectURL()
    {
        $this->redirectURL = home_url();

        if (strpos($_SERVER["HTTP_REFERER"], home_url()) !== false) {
            $this->redirectURL = explode('?', $_SERVER["HTTP_REFERER"])[0];
        }
    }

    /**
     * Redirect with errors.
     *
     * @param array $errors
     * @return void
     */
    private function redirectBackWithError($errors = false)
    {
        $redirectURL = $this->redirectURL;

        if ($errors) {
            $formID = isset($_POST['id']) ? $_POST['id'] : '';

            $redirectURL = "{$redirectURL}?form={$formID}&form-status=error";
        }

        wp_safe_redirect($redirectURL);

        exit();
    }
}
