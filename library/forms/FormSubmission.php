<?php

if (!defined('ABSPATH')) {
    exit;
}

class FormSubmission
{
    /**
     * The accepted form fields.
     *
     * 'validate' is used for validation. Available 'validate' values are:
     *  name - regex check
     *  email - regex check
     *  phone - regex check
     *  exists - isEmpty() check
     *  boolean - 'is true' check
     *  none - no validation applied
     *
     * @var array
     */
    protected $fields = [
        'name' => [
            'validate' => 'name',
            'value' => ''
        ],
        'email' => [
            'validate' => 'email',
            'value' => ''
        ],
        'subject' => [
            'validate' => 'none',
            'value' => ''
        ],
        'message' => [
            'validate' => 'exists',
            'value' => ''
        ]
    ];

    /**
     * Set the values of the accepted form fields from the post data.
     *
     * @return void
     */
    public function setFieldValues()
    {
        foreach ($this->fields as $fieldName => $fieldInfo) {
            // Example: 'title' => ['validate' => 'name', 'value' => '']
            $value = isset($_POST[$fieldName]) ? $_POST[$fieldName] : '';
            if ($value) {
                $this->fields[$fieldName]['value'] = trim($value);
            }
        }
    }

    /**
     * Validate the form fields.
     *
     * @return boolean
     */
    public function validateFields()
    {
        $errors = [];

        foreach ($this->fields as $fieldName => $fieldInfo) {
            // Example: 'title' => ['validate' => 'name', 'value' => '']
            $value = $fieldInfo['value'];
            switch ($fieldInfo['validate']) {
                case 'name':
                    $string_exp = "/^[A-Za-z .'-]+$/";
                    if (strlen($value) < 2 || !preg_match($string_exp, $value)) {
                        $errors[$fieldName] = 'Please enter a valid name';
                    }
                    break;

                case 'email':
                    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
                    if (strlen($value) < 5 || !preg_match($email_exp, $value)) {
                        $errors[$fieldName] = 'Please enter a valid email';
                    }
                    break;

                case 'phone':
                    # code...
                    break;

                case 'exists':
                    if (strlen($value) < 2) {
                        $errors[$fieldName] = 'Please enter a value';
                    }
                    break;

                case 'boolean':
                    if ($value !== true && $value !== 1 && $value !== 'on') {
                        $errors[$fieldName] = 'This must be true';
                    }
                    break;

                case 'none':
                    break;

                default:
                    break;
            }
        }

        if ($errors) {
            setcookie('form_errors', json_encode($errors), 0, '/');
            setcookie('old_inputs', json_encode($this->fields), 0, '/');

            return $errors;
        }

        setcookie('form_errors', '', 0, '/');
        setcookie('old_inputs', '', 0, '/');

        return false;
    }

    /**
     * Save form entry to database.
     *
     * @return void
     */
    public function saveEntry($postTitle, $formType)
    {
        // Create Post
        $post_id = wp_insert_post(array(
            'post_type'     => 'form_entry',
            'post_title'    => $postTitle . ' ' . uniqid(),
            'post_status'   => 'publish',
        ));

        // Add Data
        if ($post_id) {
            // insert post meta
            add_post_meta($post_id, 'form_type', $formType);
            add_post_meta($post_id, 'submission_date', date('d/m/Y'));

            foreach ($this->fields as $fieldName => $fieldInfo) {
                // Example: 'title' => ['validate' => 'name', 'value' => '']
                add_post_meta($post_id, $formType . '_form_field__' . $fieldName, $fieldInfo['value']);
            }
        }
    }

    /**
     * Sent Notification email to client with all form details
     *
     * @return void
     */
    public function sendNotificationMail($emailNotifications, $subject, $fromName, $fromEmail, $replyToEmail = '')
    {
        if ($emailNotifications) {
            $message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
            $message .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
            $message .= "<head>";
            $message .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
            $message .= "<title>" . $subject . "</title>";
            $message .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>";
            $message .= "</head><body>";

            foreach ($this->fields as $fieldName => $fieldInfo) {
                // Example: 'title' => ['validate' => 'name', 'value' => '']
                $message .= $this->formatFieldLabel($this->formatFieldName($fieldName)) . $this->formatFieldText($fieldInfo['value']);
            }

            $message .= "</body></html>";

            $headers = [
                'From: ' . $fromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $replyToEmail,
                'Content-type: text/html; charset=UTF-8'
            ];

            foreach ($emailNotifications as $notification) {
                if ($notification['receiving_email_address']) {
                    wp_mail($notification['receiving_email_address'], $subject, $message, $headers);
                }
            }
        }
    }

    /**
     * Send a notification to the client upon the submission of the form. Pass in the variables to generate the email and send.
     *
     * @param [boolean] $notificationStatus - Example: get_field('contact_form__client_email_notifications_status', 'options')
     * @param [text] $fromEmail - Example: get_field('global_forms_from_name', 'options')
     * @param [email] $fromEmail - Example: get_field('global_forms_from_email', 'options')
     * @param [text] $recipient - Example: sally@gmail.com
     * @param [text] $subject - Example: get_field('contact_form__client_email_subject', 'options')
     * @param [wysiwyg] $content - Example: get_field('contact_form__client_email_content', 'options')
     * @return void
     */
    public function sendClientEmail($notificationStatus, $fromName, $fromEmail, $recipient, $subject, $content)
    {
        // Check essential variables are set
        if ($notificationStatus == false || $recipient == '' || $content == '') {
            return;
        }

        $f = $this->fields;

        // Make sure a subject is set
        $subject = $subject ?: 'Thank you for your enquiry';

        $message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
        $message .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
        $message .= "<head>";
        $message .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
        $message .= "<title>" . $subject . "</title>";
        $message .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>";
        $message .= "<style>
                        h1, h2, h3, h4, h5, h6 {
                            margin: 0 0 .7em;
                            font-family: 'Neuzeit Grotesk', Arial, sans-serif;
                            line-height: 1.1;
                        }
                        html, body {
                            min-width: 320px;
                            margin: 10px;
                            color: #454545;
                            font-family: 'Neuzeit Grotesk', Arial, sans-serif;
                            line-height: 1.3;
                        }
                    </style>";
        $message .= "</head><body>";
        $message .= wpautop($content);
        $message .= "</body></html>";

        $from = $fromEmail ? 'From: ' . $fromName . ' <' . $fromEmail . '>' : '';
        $headers = [
            $from,
            'Reply-To: ' . $fromName . ' <' . $fromEmail . '>',
            'Content-type: text/html; charset=UTF-8'
        ];

        wp_mail($recipient, $subject, $message, $headers);
    }

    /**
     * Format the given field name as a smart title.
     *
     * @param string $label
     * @return string
     */
    public function formatFieldName($label = '')
    {
        return ucwords(str_replace(['_', '-'], ' ', $label));
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
