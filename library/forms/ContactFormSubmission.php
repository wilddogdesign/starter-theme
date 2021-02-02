<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once('FormSubmission.php');

class ContactFormSubmission extends FormSubmission
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
     * Submit the form.
     *
     * @return mixed
     */
    public function submit()
    {
        $validRecaptcha = $this->validateReCaptcha();

        if ($validRecaptcha) {
            $this->setFieldValues();

            $errors = $this->validateFields();

            if ($errors) {
                return $errors;
            }

            $this->saveEntry();

            $sendEmail = get_field('contact_form__email_notifications_status', 'form-settings');

            if ($sendEmail) {
                $this->sendMail();
            }

            $globalThankYouPage = get_field('contact_form__thank_you_page', 'form-settings');

            if ($globalThankYouPage) {
                wp_safe_redirect(get_permalink($globalThankYouPage->ID));

                exit;
            }

            return false;
        }

        // If $validRecaptcha == false;
        return ['recaptcha' => 'Invalid Recpatcha'];
    }

    /**
     * Validate the form fields.
     *
     * @return boolean
     */
    private function validateFields()
    {
        $f = $this->fields;
        $errors = [];

        $string_exp = "/^[A-Za-z .'-]+$/";

        if (strlen($f['contact_name']) < 2 || !preg_match($string_exp, $f['contact_name'])) {
            $errors['contact_name'] = 'Please enter a valid name';
        }

        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

        if (strlen($f['contact_email']) < 5 || !preg_match($email_exp, $f['contact_email'])) {
            $errors['contact_email'] = 'Please enter a valid email';
        }

        if (strlen($f['subject']) < 2) {
            $errors['subject'] = 'Please enter a subject';
        }

        if (strlen($f['message']) < 2) {
            $errors['message'] = 'Please enter a message';
        }

        if ($errors) {
            setcookie('form_errors', json_encode($errors), 0, '/');
            setcookie('old_inputs', json_encode($f), 0, '/');

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
    private function saveEntry()
    {
        // Create Post
        $post_id = wp_insert_post(array(
            'post_type'     => 'form_entry',
            'post_title'    => 'Contact Form Entry ' . uniqid(),
            'post_status'   => 'publish',
        ));

        // Add Data
        if ($post_id) {
            $f = $this->fields;

            // insert post meta
            add_post_meta($post_id, 'form_type', 'contact');
            add_post_meta($post_id, 'submission_date', date('m/d/Y'));
            add_post_meta($post_id, 'name', $f['contact_name']);
            add_post_meta($post_id, 'email', $f['contact_email']);
            add_post_meta($post_id, 'subject', $f['subject']);
            add_post_meta($post_id, 'message', $f['message']);
        }
    }

    private function sendMail()
    {
        $f = $this->fields;

        $message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
        $message .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
        $message .= "<head>";
        $message .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
        $message .= "<title>Contact Us</title>";
        $message .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>";
        $message .= "</head><body>";

        $message .= $this->formatFieldLabel("Contact Name") . $this->formatFieldText($f['contact_name']);
        $message .= $this->formatFieldLabel("Contact Email") . $this->formatFieldText($f['contact_email']);
        $message .= $this->formatFieldLabel("Subject") . $this->formatFieldText($f['subject']);
        $message .= $this->formatFieldLabel("Message") . $this->formatFieldText($f['message']);

        $message .= "</body></html>";

        $headers = [
            'From: noreply@tbc.com',
            'Reply-To: ' . $f['contact_email'],
            'Content-type: text/html; charset=UTF-8'
        ];

        $subject = 'New Contact Form Submission - ' . $f['subject'];

        $emailNotifcations = get_field('contact_form__email_notifications', 'form-settings');
        if ($emailNotifcations) {
            foreach ($emailNotifcations as $notifcation) {
                if ($notifcation['receiving_email_address']) {
                    wp_mail($notifcation['receiving_email_address'], $subject, $message, $headers);
                }
            }
        }
    }
}
