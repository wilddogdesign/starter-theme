<?php

if (!defined('ABSPATH')) {
    exit;
}

class FormSubmission
{
    /**
     * The accepted form fields.
     *
     * @var array
     */
    protected $fields = [
        'contact_name' => '',
        'contact_email' => '',
        'subject' => '',
        'message' => ''
    ];

    /**
     * Set the values of the accepted form fields from the post data.
     *
     * @return void
     */
    public function setFieldValues()
    {
        foreach ($this->fields as $key => $value) {
            if (isset($_POST[$key])) {
                $this->fields[$key] = trim($_POST[$key]);
            }
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

        $secret = get_field('google__recaptcha_secret', 'options') ? get_field('google__recaptcha_secret', 'options') : false;

        // calling google recaptcha api.
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

        $res = json_decode($response);

        return $res->success;
    }

    /**
     * Format the given string as a label HTML element.
     *
     * @param string $label
     * @return string
     */
    public function formatFieldLabel($label = '')
    {
        return "<div style=\"font-weight: bold\">{$label}</div>";
    }

    /**
     * Format the given string as a text HTML element.
     *
     * @param string $label
     * @return string
     */
    public function formatFieldText($text = '')
    {
        return "<div>" . $this->cleanString($text) . "</div><br />";
    }

    /**
     * clean the given string for use in an email.
     *
     * @param string $label
     * @return string
     */
    public function cleanString($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }
}
