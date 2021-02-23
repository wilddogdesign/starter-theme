<?php

function registerACFFormFieldGroup($forms)
{
    if (function_exists('acf_add_local_field_group')) {

        acf_add_local_field_group([
            'key' => 'form-entry-fields',
            'title' => 'Form Entry Fields',
            'fields' => [
                [
                    'key' => 'field_form_type',
                    'label' => 'Form Type',
                    'name' => 'form_type',
                    'type' => 'select',
                    'wrapper' => ['width' => '50'],
                    'choices' => $forms
                ],
                [
                    'key' => 'field_submission_date',
                    'label' => 'Submission Date',
                    'name' => 'submission_date',
                    'type' => 'text',
                    'wrapper' => ['width' => '50']
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'form_entry',
                    ],
                ],
            ],
        ]);

        return 'form-entry-fields';
    }

    return false;
}

require_once(get_template_directory() . '/library/forms/FormSubmission.php');

function registerACFFormFields($formType, $fieldGroup, $fields)
{
    $formSubmission = new FormSubmission();

    foreach ($fields as $fieldName => $fieldType) {
        // Get field see if it exists
        // don't allow overrides: some manually created fields (via .php) used duplicate keys (copy of original field)
        if (acf_is_local_field($fieldName) == false) {
            // if not add field
            if ($fieldType == 'boolean') {
                acf_add_local_field([
                    'key' => $formType . '_form_field__' . $fieldName,
                    'label' => $formSubmission->formatFieldName($fieldName),
                    'name' => $formType . '_form_field__' . $fieldName,
                    'type' => 'true_false',
                    'parent' => $fieldGroup,
                    'ui' => 1,
                    'ui_on_text' => 'On',
                    'ui_off_text' => 'Off',
                    'readonly' => 1,
                    'wrapper' => [
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ],
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_form_type',
                                'operator' => '==',
                                'value' => $formType
                            ]
                        ]
                    ]
                ]);
            } elseif ($fieldType == 'textarea') {
                acf_add_local_field([
                    'key' => $formType . '_form_field__' . $fieldName,
                    'label' => $formSubmission->formatFieldName($fieldName),
                    'name' => $formType . '_form_field__' . $fieldName,
                    'type' => 'textarea',
                    'rows' => 4,
                    'parent' => $fieldGroup,
                    'readonly' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_form_type',
                                'operator' => '==',
                                'value' => $formType
                            ]
                        ]
                    ]
                ]);
            } else {
                acf_add_local_field([
                    'key' => $formType . '_form_field__' . $fieldName,
                    'label' => $formSubmission->formatFieldName($fieldName),
                    'name' => $formType . '_form_field__' . $fieldName,
                    'type' => 'text',
                    'parent' => $fieldGroup,
                    'readonly' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_form_type',
                                'operator' => '==',
                                'value' => $formType
                            ]
                        ]
                    ]
                ]);
            }
        }
    }
}
