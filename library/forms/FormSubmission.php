<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once(get_template_directory() . '/library/helpers/getUTMs.php');
require_once(get_template_directory() . '/library/helpers/isJSON.php');

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
     * Setup the utms of the form fields.
     *
     * @return boolean
     */
    public function setUTMFields()
    {
        $utms = getUTMs();

        if ($utms) {
            foreach ($utms as $utm) {
                $this->fields[$utm] = [
                    'validate' => 'none',
                    'value' => ''
                ];
            }
        }
    }

    /**
     * Set the values of the accepted form fields from the post data.
     *
     * @return void
     */
    public function setFieldValues()
    {
        foreach ($this->fields as $fieldName => $fieldInfo) {
            // if a repeated field: example: 'route' => ['items' => ['leg_0' => ['name' => ['validate' => '', 'value' => '']]]]
            if (isset($fieldInfo['items'])) {
                foreach ($fieldInfo['items'] as $legKey => $legInfo) {
                    foreach ($legInfo as $itemKey => $itemInfo) {
                        $value = isset($_POST[$itemKey]) ? $_POST[$itemKey] : '';
                        if ($value) {
                            $this->fields[$fieldName]['items'][$legKey][$itemKey]['value'] = trim($value);
                        }
                    }
                }
            } else {
                // Example: 'title' => ['validate' => 'name', 'value' => '']
                $value = isset($_POST[$fieldName]) ? $_POST[$fieldName] : '';
                if ($value) {
                    $this->fields[$fieldName]['value'] = trim($value);
                }
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

        // Create array of fields to validate (only needed becasue of repeater fields)
        $fieldsToValidate = [];
        foreach ($this->fields as $fieldName => $fieldInfo) {
            // Example: 'title' => ['validate' => 'name', 'value' => '']
            // if a repeated field: example: 'route' => ['items' => ['leg_0' => ['name' => ['validate' => '', 'value' => '']]]]
            if (isset($fieldInfo['items'])) {
                foreach ($fieldInfo['items'] as $item) {
                    foreach ($item as $itemKey => $itemInfo) {
                        $fieldsToValidate[$itemKey] = $itemInfo;
                    }
                }
            } else {
                $fieldsToValidate[$fieldName] = $fieldInfo;
            }
        }

        foreach ($fieldsToValidate as $fieldName => $fieldInfo) {
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

                    // case 'date':
                    //     $dateArray = explode('-', $value);
                    //     if (!checkdate($dateArray[1], $dateArray[0], $dateArray[2])) {
                    //         $errors[$fieldName] = 'Invalid date';
                    //     }
                    //     break;

                    // case 'time':
                    //     // 24hr time format HH:MM
                    //     $timeFormat = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/';
                    //     if (strlen($value) < 4 || !preg_match($timeFormat, $value)) {
                    //         $errors[$fieldName] = 'Please enter a valid time';
                    //     }
                    //     break;

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
                // if a repeated field: example: 'route' => ['items' => ['item_0' => ['name' => ['validate' => '', 'value' => '']]]]
                if (isset($fieldInfo['items'])) {
                    $repeaterData = []; // each element of this array is a row in the repeater ($itemData)

                    foreach ($fieldInfo['items'] as $item) {
                        // Example ['item_0' => ['name' => ['validate' => '', 'value' => '']]]]
                        $itemData = []; // each element of this row is a field key => value pair
                        foreach ($item as $subFieldName => $subFieldInfo) {
                            // Example ['name' => ['validate' => '', 'value' => '']]]
                            $strippedSubFieldName = explode('-', $subFieldName);
                            // Note: You MUST use field keys here
                            // Example: field_cargo_form_field__route_subfield__departure
                            $subFieldKey = 'field_' . $formType . '_form_field__' . $fieldName . '_subfield__' . substr($strippedSubFieldName[1], 2);
                            // Add the subfield name and value to the item details
                            $itemData[$subFieldKey] = $subFieldInfo['value'];
                        }

                        // Add the item data to the repeater data
                        $repeaterData[] = $itemData;
                    }

                    // Note: You MUST use field keys here
                    $repeaterKey = 'field_' . $formType . '_form_field__' . $fieldName; // Example field_cargo_form_field__route
                    update_field($repeaterKey, $repeaterData, $post_id);
                } else {
                    // Example: 'title' => ['validate' => 'name', 'value' => '']
                    $fieldValue = $fieldInfo['value'];
                    add_post_meta($post_id, $formType . '_form_field__' . $fieldName, $fieldValue);
                }
            }
        }
    }

    /**
     * Sent Notification email to client with all form details
     *
     * @return void
     */
    public function sendNotificationMail($notificationStatus, $fromName, $fromEmail, $replyToEmail = '', $emailNotifications, $subject = '', $content = '')
    {
        // Check essential variables are set
        if ($notificationStatus == false || $emailNotifications == false || $replyToEmail == '') {
            return;
        }

        if ($emailNotifications) {
            $f = $this->fields;

            // Make sure a subject is set
            $subject = $subject ?: 'New Form Submission';

            $message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
            $message .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
            $message .= "<head>";
            $message .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
            $message .= "<title>" . $subject . "</title>";
            $message .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>";
            $message .= "<style>
                            h1, h2, h3, h4, h5, h6 {
                                margin: 0 0 .7em;
                                font-family: Arial, sans-serif;
                                font-weight: 700;
                            }
                            html, body {
                                min-width: 320px;
                                margin: 10px;
                                color: #414042;
                                font-family: Arial, sans-serif;
                            }
                            table {
                                font-family: arial, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                            }
                            td, th {
                                border: 1px solid #dddddd;
                                text-align: left;
                                padding: 8px;
                            }
                            tr:nth-child(even) {
                                background-color: #dddddd;
                            }
                        </style>";
            $message .= "</head><body>";

            if ($content) {
                $message .= wpautop($this->formatDynamicContent($content));
            } else {
                $message .= "<h2>Submitted Data</h2>";
                $message .= $this->formatFieldLabel('Submission Date') . $this->formatFieldText(date("d/m/Y")); // Add Submission Date

                foreach ($this->fields as $fieldName => $fieldInfo) {
                    // Example: 'title' => ['validate' => 'name', 'value' => '']
                    // if a repeated field: example: 'shipments' => ['items' => ['item_0' => ['name' => ['validate' => '', 'value' => '']]]]
                    if (isset($fieldInfo['items'])) {
                        $message .= $this->formatFieldLabel($this->formatFieldName($fieldName)); // Add <h4>Shipments</h4>
                        $message .= "<table>";
                        $message .= "<tr>";

                        // Set the table headers
                        foreach ($fieldInfo['items'] as $itemKey => $itemInfo) {
                            if ($itemKey === array_key_first($fieldInfo['items'])) {
                                foreach ($itemInfo as $itemFieldName => $itemFieldInfo) {
                                    $strippedSubFieldName = explode('-', $itemFieldName);
                                    $message .= "<th>" . $this->formatFieldName(substr($strippedSubFieldName[1], 2)) . "</th>";
                                }
                            }
                        }

                        $message .= "</tr>";

                        // Set the table content
                        foreach ($fieldInfo['items'] as $itemInfo) {
                            $message .= "<tr>";
                            foreach ($itemInfo as $itemFieldName => $itemFieldInfo) {
                                if ($itemFieldInfo['value']) {
                                    // Dont print blank fields or programatic or json fields
                                    $message .= "<td>" . $this->formatFieldText($itemFieldInfo['value']) . "</td>";
                                }
                            }
                            $message .= "</tr>";
                        }

                        $message .= "</table>";
                    } elseif ($fieldInfo['value']) { // Dont print blank fields or programatic or json fields
                        // Example: 'title' => ['validate' => 'name', 'value' => '']
                        $message .= $this->formatFieldLabel($this->formatFieldName($fieldName)) . $this->formatFieldText($fieldInfo['value']);
                    }
                }
            }

            $message .= "</body></html>";
            $headers = [
                'From: ' . $fromName . ' <' . $fromEmail . '>',
                'Reply-To: ' . $replyToEmail,
                'Content-type: text/html; charset=UTF-8'
            ];

            foreach ($emailNotifications as $notification) {
                $emailAddress = $notification['receiving_email_address'];
                if ($emailAddress) {
                    wp_mail($emailAddress, $subject, $message, $headers);
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
                            font-family: Arial, sans-serif;
                            font-weight: 700;
                        }
                        html, body {
                            min-width: 320px;
                            margin: 10px;
                            color: #414042;
                            font-family: Arial, sans-serif;
                        }
                        table {
                            font-family: arial, sans-serif;
                            border-collapse: collapse;
                            width: 100%;
                        }
                        td, th {
                            border: 1px solid #dddddd;
                            text-align: left;
                            padding: 8px;
                        }
                        tr:nth-child(even) {
                            background-color: #dddddd;
                        }
                    </style>";
        $message .= "</head><body>";
        $message .= wpautop($this->formatDynamicContent($content));
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
     * Get the thank you page URL
     *
     * @return boolean
     */
    public function getThankYouPage($fieldname)
    {
        $globalThankYouPage = get_field($fieldname, 'options');

        if (is_object($globalThankYouPage)) {
            return get_permalink($globalThankYouPage->ID);
        } else {
            return $globalThankYouPage ? get_permalink($globalThankYouPage) : false;
        }
    }

    /**
     * Get the thank you page URL for the correct language
     *
     * @return boolean
     */
    public function getWPMLThankYouPage($lang, $fieldname)
    {
        $globalThankYouPage = get_field($lang . '_' . $fieldname, 'options');

        if (is_object($globalThankYouPage)) {
            $globalThankYouPageURL = get_permalink($globalThankYouPage->ID);
        } else {
            $globalThankYouPageURL = $globalThankYouPage ? get_permalink($globalThankYouPage) : false;
        }

        return apply_filters('wpml_permalink', $globalThankYouPageURL, $lang);
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

    /**
     * Format the given string and replace {fieldname} with values.
     *
     * @param string $text
     * @return string
     */
    public function formatDynamicContent($text = '')
    {
        $fields = $this->fields;
        if ($fields) {
            foreach ($this->fields as $fieldName => $fieldInfo) {
                // Example: 'title' => ['validate' => 'name', 'value' => '']
                // if a repeated field: example: 'route' => ['items' => ['leg' => ['name' => ['validate' => '', 'value' => '']]]]
                if (isset($fieldInfo['items'])) {
                    // Create the table incase its referenced
                    $table = "<table>";
                    $table .= "<tr>";
                    // Set the table headers
                    foreach ($fieldInfo['items'] as $legID => $legInfo) {
                        if ($legID === array_key_first($fieldInfo['items'])) {
                            foreach ($legInfo as $itemName => $itemInfo) {
                                $strippedSubFieldName = explode('-', $itemName);
                                $table .= "<th>" . $this->formatFieldName(substr($strippedSubFieldName[1], 2)) . "</th>";
                            }
                        }
                    }
                    $table .= "</tr>";
                    // Set the table content
                    foreach ($fieldInfo['items'] as $legInfo) {
                        $table .= "<tr>";
                        foreach ($legInfo as $itemKey => $itemInfo) {
                            if ($itemInfo['value']) {
                                // Dont print blank fields or programatic or json fields
                                $table .= "<td>" . $this->formatFieldText($itemInfo['value']) . "</td>";
                            }
                        }
                        $table .= "</tr>";
                    }
                    $table .= "</table>";
                    // replace references to the table if needed
                    $text = str_replace("{" . $fieldName . "}", $table, $text);
                } elseif ($fieldInfo['value']) {
                    // Example: 'title' => ['validate' => 'name', 'value' => '']
                    $text = str_replace("{" . $fieldName . "}", $this->formatFieldText($fieldInfo['value']), $text);
                } else {
                    // If no value, dont show!
                    // Example: 'title' => ['validate' => 'name', 'value' => '']
                    $text = str_replace("{" . $fieldName . "}", '', $text);
                }
            }
        }

        return $text;
    }
}
