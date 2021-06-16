<?php
if (!defined('ABSPATH')) {
    exit;
}

class DownloadFormEntries
{
    /**
     * Download CSV of Form Entries
     *
     * @param string $form
     * @return CSV
     */
    public function download($form)
    {
        error_log('Generating CSV on ' . date('Y/m/d'));
        $data = false;

        switch ($form) {

            // enews columns
            case 'enews_form_all_detailed':
                $documentTitle = 'All-Enews-Form-Submission-' . date('Y/m/d') . '.csv';
                $columns = [
                    'FORM TYPE',
                    'SUBMISSION DATE',
                    'FIRST NAME',
                    'SURNAME',
                    'EMAIL'
                ];
                $data = $this->enewsAllSubmissions();
                break;
            case 'enews_form_all':
                $documentTitle = 'All-Enews-Form-Submissions-Emails' . date('Y/m/d') . '.csv';
                $columns = [
                    'EMAIL'
                ];
                $data = $this->enewsAllEmails();
                break;
            case 'enews_form_date_range':
                $startDate = date("Ymd", strtotime($_POST['start_date']));
                $endDate = date("Ymd", strtotime($_POST['end_date']));
                $documentTitle = 'All-Enews-Form-Submissions-Between-' . $startDate . '-and-' . $endDate . '.csv';
                $columns = [
                    'FORM TYPE',
                    'SUBMISSION DATE',
                    'FIRST NAME',
                    'SURNAME',
                    'EMAIL'
                ];
                $data = $this->enewsAllSubmissionsBetween($startDate, $endDate);
                break;

            // contact columns
            case 'contact_form_all_detailed':
                $documentTitle = 'All-Contact-Form-Submission-' . date('Y/m/d') . '.csv';
                $columns = [
                    'FORM TYPE',
                    'SUBMISSION DATE',
                    'TITLE',
                    'FIRST NAME',
                    'SURNAME',
                    'GROUP NAME',
                    'EMAIL',
                    'TELEPHONE',
                    'MESSAGE',
                    'HEARD OF US',
                    'NEWSLETER'
                ];
                $data = $this->contactAllSubmissions();
                break;
            case 'contact_form_all':
                $documentTitle = 'All-Contact-Form-Submissions-Emails' . date('Y/m/d') . '.csv';
                $columns = [
                    'EMAIL'
                ];
                $data = $this->contactAllEmails();
                break;
            case 'contact_form_date_range':
                $startDate = date("Ymd", strtotime($_POST['start_date']));
                $endDate = date("Ymd", strtotime($_POST['end_date']));
                $documentTitle = 'All-Contact-Form-Submissions-Between-' . $startDate . '-and-' . $endDate . '.csv';
                $columns = [
                    'FORM TYPE',
                    'SUBMISSION DATE',
                    'TITLE',
                    'FIRST NAME',
                    'SURNAME',
                    'GROUP NAME',
                    'EMAIL',
                    'TELEPHONE',
                    'MESSAGE',
                    'HEARD OF US',
                    'NEWSLETER'
                ];
                $data = $this->contactAllSubmissionsBetween($startDate, $endDate);
                break;
        }

        if ($data && $columns) {
            $this->sendHeaders($documentTitle);
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            die();
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&errors=true');
        }
    }

    /* Helper to set Response Headers */
    public function sendHeaders($title)
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . $title . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    // enews all emails
    public function enewsAllEmails()
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'enews',
                'compare'   => 'LIKE'
            ]
        ));

        if ($entries) {
            foreach ($entries as $entry) {
                $stats[] = [$entry->enews_form_field__email];
            }
        }

        return $stats;
    }

    // enews all submissions
    public function enewsAllSubmissions()
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'enews',
                'compare'   => 'LIKE'
            ]
        ));

        // UK Date format - arrange in corrected order
        if ($entries) {
            usort($entries, function ($a, $b) {
                $date1 = $a->submission_date;
                $date2 = $b->submission_date;
                //Since the income date is formatted like this d/m/Y , we have to change it
                $date1 = date_format(date_create_from_format('d/m/Y', $date1), 'Y-m-d');
                $date2 = date_format(date_create_from_format('d/m/Y', $date2), 'Y-m-d');
                if ($date1 > $date2) {
                    return -1;
                }
                if ($date1 == $date2) {
                    return 0;
                }
                if ($date1 < $date2) {
                    return 1;
                }
            });
        }

        if ($entries) {
            foreach ($entries as $entry) {
                $stats[] = [
                    ucfirst($entry->form_type),
                    $entry->submission_date,
                    $entry->enews_form_field__first_name,
                    $entry->enews_form_field__surname,
                    $entry->enews_form_field__email
                ];
            }
        }

        return $stats;
    }

    // enews all submissions between
    /**
     * Undocumented function
     *
     * @param [type] $startDate - Example 01052021
     * @param [type] $endDate - Example 10052021
     * @return void
     */
    public function enewsAllSubmissionsBetween($startDate, $endDate)
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'enews',
                'compare'   => 'LIKE'
            ]
        ));

        // UK Date format - arrange in corrected order
        if ($entries) {
            usort($entries, function ($a, $b) {
                $date1 = $a->submission_date;
                $date2 = $b->submission_date;
                //Since the income date is formatted like this d/m/Y , we have to change it
                $date1 = date_format(date_create_from_format('d/m/Y', $date1), 'Y-m-d');
                $date2 = date_format(date_create_from_format('d/m/Y', $date2), 'Y-m-d');
                if ($date1 > $date2) {
                    return -1;
                }
                if ($date1 == $date2) {
                    return 0;
                }
                if ($date1 < $date2) {
                    return 1;
                }
            });
        }

        if ($entries) {
            foreach ($entries as $entry) {
                // Format the dates to reverse order:
                $formattedSubmissionDate = date("Ymd", strtotime(str_replace('/', '-', $entry->submission_date)));
                // $formattedStartDate = date("Ymd", $startDate);
                // $formattedEndDate = date("Ymd", $endDate);
                if ($formattedSubmissionDate >= $startDate && $formattedSubmissionDate <= $endDate) {
                    $stats[] = [
                        ucfirst($entry->form_type),
                        $entry->submission_date,
                        $entry->enews_form_field__first_name,
                        $entry->enews_form_field__surname,
                        $entry->enews_form_field__email
                    ];
                }
            }
        }

        return $stats;
    }

    // contact all emails
    public function contactAllEmails()
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'contact',
                'compare'   => 'LIKE'
            ]
        ));

        if ($entries) {
            foreach ($entries as $entry) {
                $stats[] = [$entry->contact_form_field__email];
            }
        }

        return $stats;
    }

    // contact all submissions
    public function contactAllSubmissions()
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'contact',
                'compare'   => 'LIKE'
            ]
        ));

        // UK Date format - arrange in corrected order
        if ($entries) {
            usort($entries, function ($a, $b) {
                $date1 = $a->submission_date;
                $date2 = $b->submission_date;
                //Since the income date is formatted like this d/m/Y , we have to change it
                $date1 = date_format(date_create_from_format('d/m/Y', $date1), 'Y-m-d');
                $date2 = date_format(date_create_from_format('d/m/Y', $date2), 'Y-m-d');
                if ($date1 > $date2) {
                    return -1;
                }
                if ($date1 == $date2) {
                    return 0;
                }
                if ($date1 < $date2) {
                    return 1;
                }
            });
        }

        if ($entries) {
            foreach ($entries as $entry) {
                $stats[] = [
                    ucfirst($entry->form_type),
                    $entry->submission_date,
                    $entry->contact_form_field__title,
                    $entry->contact_form_field__first_name,
                    $entry->contact_form_field__surname,
                    $entry->contact_form_field__group_name,
                    $entry->contact_form_field__email,
                    $entry->contact_form_field__telephone,
                    $entry->contact_form_field__message,
                    $entry->contact_form_field__heard_of_us,
                    $entry->contact_form_field__newsletter
                ];
            }
        }

        return $stats;
    }

    // contact all submissions between
    /**
     * Undocumented function
     *
     * @param [type] $startDate - Example 01052021
     * @param [type] $endDate - Example 10052021
     * @return void
     */
    public function contactAllSubmissionsBetween($startDate, $endDate)
    {
        $stats = [];
        $entries = Timber::get_posts(array(
            'post_type' => 'form_entry',
            'posts_per_page' => -1,
            'meta_key' => 'submission_date',
            'orderby' => 'meta_value_date',
            'order' => 'DESC',
            'meta_query' => [
                'key'       => 'form_type',
                'value'     => 'contact',
                'compare'   => 'LIKE'
            ]
        ));

        // UK Date format - arrange in corrected order
        if ($entries) {
            usort($entries, function ($a, $b) {
                $date1 = $a->submission_date;
                $date2 = $b->submission_date;
                //Since the income date is formatted like this d/m/Y , we have to change it
                $date1 = date_format(date_create_from_format('d/m/Y', $date1), 'Y-m-d');
                $date2 = date_format(date_create_from_format('d/m/Y', $date2), 'Y-m-d');
                if ($date1 > $date2) {
                    return -1;
                }
                if ($date1 == $date2) {
                    return 0;
                }
                if ($date1 < $date2) {
                    return 1;
                }
            });
        }

        if ($entries) {
            foreach ($entries as $entry) {
                // Format the dates to reverse order:
                $formattedSubmissionDate = date("Ymd", strtotime(str_replace('/', '-', $entry->submission_date)));
                // $formattedStartDate = date("Ymd", $startDate);
                // $formattedEndDate = date("Ymd", $endDate);
                if ($formattedSubmissionDate >= $startDate && $formattedSubmissionDate <= $endDate) {
                    $stats[] = [
                        ucfirst($entry->form_type),
                        $entry->submission_date,
                        $entry->contact_form_field__title,
                        $entry->contact_form_field__first_name,
                        $entry->contact_form_field__surname,
                        $entry->contact_form_field__group_name,
                        $entry->contact_form_field__email,
                        $entry->contact_form_field__telephone,
                        $entry->contact_form_field__message,
                        $entry->contact_form_field__heard_of_us,
                        $entry->contact_form_field__newsletter
                    ];
                }
            }
        }

        return $stats;
    }
}
