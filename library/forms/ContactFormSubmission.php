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
        'first_name' => [
            'validate' => 'name',
            'value' => ''
        ],
        'last_name' => [
            'validate' => 'name',
            'value' => ''
        ],
        'email' => [
            'validate' => 'email',
            'value' => ''
        ],
        'telephone' => [
            'validate' => 'phone',
            'value' => ''
        ],
        'message' => [
            'validate' => 'none',
            'value' => ''
        ],
        'newsletter' => [
            'validate' => 'none',
            'value' => ''
        ]
    ];

    /**
     * Submit the form.
     *
     * @return mixed
     */
    public function submit()
    {
        $this->setFieldValues();

        $errors = $this->validateFields();
        if ($errors) {
            return ['errors' => $errors];
        }

        $this->saveEntry('Contact Form Entry', 'contact');

        $sendNotificationEmail = get_field('contact_form__email_notifications_status', 'options');
        if ($sendNotificationEmail) {
            $this->sendNotificationMail(
                get_field('contact_form__email_notifications', 'options'),
                'New Contact Form Submission',
                get_field('global_forms_from_name', 'options'),
                get_field('global_forms_from_email', 'options'),
                $this->fields['email']['value']
            );
        }

        $this->sendClientEmail(
            get_field('contact_form__client_email_notifications_status', 'options'),
            get_field('global_forms_from_name', 'options'),
            get_field('global_forms_from_email', 'options'),
            $this->fields['email']['value'],
            get_field('contact_form__client_email_subject', 'options'),
            get_field('contact_form__client_email_content', 'options')
        );

        $globalThankYouPage = get_field('contact_form__thank_you_page', 'options');
        $globalThankYouPageURL = $globalThankYouPage ? get_permalink($globalThankYouPage->ID) : false;

        return [
            'errors' => false,
            'params' => [
                'thankYouPageURL' => $globalThankYouPageURL
            ]
        ];
    }
}
