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

        switch ($_POST['form']) {
            case 'contact':
                $this->submitContactForm();

                break;
        }

        $this->redirectBackWithError();
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

    /**
     * Submit a contact form.
     *
     * @return void
     */
    private function submitContactForm()
    {
        require_once('ContactFormSubmission.php');
        $form = new ContactFormSubmission();

        // if successful the form will redirect and not return errors
        $errors = $form->submit();

        if ($errors) {
            $this->redirectBackWithError($errors);
        } else {
            $formID = isset($_POST['id']) ? $_POST['id'] : '';

            wp_safe_redirect($this->redirectURL . "?form={$formID}&form-status=success");
        }
    }
}

new FormHandler();
