<?php

require_once(get_template_directory() . '/library/forms/FormFields.php'); // Get all the information
require_once(get_template_directory() . '/library/forms/FormSubmission.php');
$formSubmission = new FormSubmission();

// Setup forms for the dropdown
$formKeys = array_keys($forms);
$choices = [];
foreach ($formKeys as $key) {
    $choices[$key] = ucwords($key);
}

// Setup Initial 2 fields - same for all forms
$fields = [
    [
        'key' => 'field_form_type',
        'label' => 'Form Type',
        'name' => 'form_type',
        'type' => 'select',
        'wrapper' => ['width' => '50'],
        'choices' => $choices
    ],
    [
        'key' => 'field_submission_date',
        'label' => 'Submission Date',
        'name' => 'submission_date',
        'type' => 'text',
        'wrapper' => ['width' => '50']
    ]
];

// Setup dynamic fields for each form
foreach ($forms as $formKey => $form) {
    foreach ($form as $formField => $fieldType) {
        if (is_array($fieldType)) {
            // Repeater field
            $subFields = [];
            foreach ($fieldType as $subFieldName => $subFieldType) {
                if ($subFieldType == 'date') {
                    $subFields[] = [
                        'key' => 'field_' . $formKey . '_form_field__' . $formField . '_subfield__' . $subFieldName,
                        'label' => $formSubmission->formatFieldName($subFieldName),
                        'name' => $subFieldName,
                        'type' => 'date_picker',
                        'readonly' => 1,
                        'display_format' => 'd/m/Y',
                        'return_format' => 'd/m/Y'
                    ];
                } elseif ($subFieldType == 'time') {
                    $subFields[] = [
                        'key' => 'field_' . $formKey . '_form_field__' . $formField . '_subfield__' . $subFieldName,
                        'label' => $formSubmission->formatFieldName($subFieldName),
                        'name' => $subFieldName,
                        'type' => 'time_picker',
                        'readonly' => 1,
                        'display_format' => 'g:i a',
                        'return_format' => 'g:i a'
                    ];
                } else {
                    $subFields[] = [
                        'key' => 'field_' . $formKey . '_form_field__' . $formField . '_subfield__' . $subFieldName,
                        'label' => $formSubmission->formatFieldName($subFieldName),
                        'name' => $subFieldName,
                        'type' => 'text',
                        'readonly' => 1
                    ];
                }
            };
            $fields[] = [
                'key' => 'field_' . $formKey . '_form_field__' . $formField,
                'label' => $formSubmission->formatFieldName($formField),
                'name' => $formKey . '_form_field__' . $formField,
                'type' => 'repeater',
                'instructions' => '',
                'readonly' => 1,
                'sub_fields' => $subFields,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_form_type',
                            'operator' => '==',
                            'value' => $formKey
                        ]
                    ]
                ]
            ];
        } elseif ($fieldType == 'boolean') {
            // True/False field
            $fields[] = [
                'key' => 'field_' . $formKey . '_form_field__' . $formField,
                'label' => $formSubmission->formatFieldName($formField),
                'name' => $formKey . '_form_field__' . $formField,
                'type' => 'true_false',
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
                            'value' => $formKey
                        ]
                    ]
                ]
            ];
        } elseif ($fieldType == 'textarea') {
            // Text Area field
            $fields[] = [
                'key' => 'field_' . $formKey . '_form_field__' . $formField,
                'label' => $formSubmission->formatFieldName($formField),
                'name' => $formKey . '_form_field__' . $formField,
                'type' => 'textarea',
                'rows' => 4,
                'readonly' => 1,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_form_type',
                            'operator' => '==',
                            'value' => $formKey
                        ]
                    ]
                ]
            ];
        } elseif ($fieldType == 'small') {
            // Text 33% field
            $fields[] = [
                'key' => 'field_' . $formKey . '_form_field__' . $formField,
                'label' => $formSubmission->formatFieldName($formField),
                'name' => $formKey . '_form_field__' . $formField,
                'type' => 'text',
                'readonly' => 1,
                'wrapper' => [
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ],
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_form_type',
                            'operator' => '==',
                            'value' => $formKey
                        ]
                    ]
                ]
            ];
        } else {
            // Text field
            $fields[] = [
                'key' => 'field_' . $formKey . '_form_field__' . $formField,
                'label' => $formSubmission->formatFieldName($formField),
                'name' => $formKey . '_form_field__' . $formField,
                'type' => 'text',
                'readonly' => 1,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_form_type',
                            'operator' => '==',
                            'value' => $formKey
                        ]
                    ]
                ]
            ];
        }
    }
}

$group = [
    'key' => 'form-entry-fields',
    'title' => 'Form Entry Fields',
    'fields' => $fields,
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'form_entry',
            ],
        ],
    ],
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
        0 => 'the_content',
        1 => 'comments',
    ),
    'active' => 1,
    'description' => '',
    "modified" => time()
];
