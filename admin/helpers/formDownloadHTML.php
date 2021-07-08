<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_GET['errors'])) {
    echo "<script type='text/javascript'>alert('no results found');</script>";
    // error to do
}

$formAction = admin_url('admin.php?page=forms-download&noheader=true');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once(ABSPATH . '/wp-load.php');

    if (current_user_can('manage_options') && isset($_POST['form-type'])) {
        include('DownloadFormEntries.php');
        $type = $_POST['form-type'];
        $DownloadFormEntries = new DownloadFormEntries();
        $DownloadFormEntries->download($type);
    }
}

require_once(ABSPATH . 'wp-admin/includes/screen.php');

$currentScreen = get_current_screen();
if ($currentScreen) {
    if ($currentScreen->base != 'form_entry_page_forms-download') {
        return;
    }
}

?>
<style>
    .meta-box {
        background: #FFF;
        width: 40%;
        padding: 25px;
        float: left;
        margin-bottom: 20px;
        box-shadow: 2px 4px 9px #0000003b;
    }

    .meta-box:nth-of-type(even) {
        margin-right: 10px;
    }

    h2 {
        margin-top: 0;
    }

    hr {
        margin-bottom: 15px;
    }

    form {
        margin: 10px 0;
    }

    label {
        display: block;
        margin: 10px 0;
    }
</style>

<?php
$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>

<div class="wrap">
    <h1>Download Form Entries</h1>

    <nav class="nav-tab-wrapper">
        <a href="?post_type=form_entry&page=forms-download" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>">Enews</a>
        <a href="?post_type=form_entry&page=forms-download&tab=contact" class="nav-tab <?php if ($tab === 'contact') : ?>nav-tab-active<?php endif; ?>">Contact</a>
    </nav>

    <div class="tab-content">
        <?php switch ($tab):
            case 'contact':
        ?>
                <div class="wrap acf-settings-wrap">
                    <h2>Contact Form Entries</h2>
                    <p>Set the fields to generate downloadable data</p>

                    <div class='meta-box'>
                        <h3>All Contact Form Submissions (Detailed):</h3>
                        <form method="post" action="<?php echo $formAction ?>">
                            <input type="hidden" name="form-type" value="contact_form_all_detailed" />
                            <input type="submit" class="button" value="Generate & Download" />
                        </form>
                    </div>

                    <div class='meta-box'>
                        <h3>All Contact Form Submission Email Addresses:</h3>
                        <form method="post" action="<?php echo $formAction ?>">
                            <input type="hidden" name="form-type" value="contact_form_all" />
                            <input type="submit" class="button" value="Generate & Download" />
                        </form>
                    </div>

                    <div class='meta-box'>
                        <h3>Selected Contact Form Submissions:</h3>
                        <form method="post" action="<?php echo $formAction ?>">
                            <input type="hidden" name="form-type" value="contact_form_date_range" />
                            <label>
                                Start Date:
                                <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </label>

                            <label>
                                End Date:
                                <input type="date" name="end_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </label>
                            <input type="submit" class="button" value="Generate & Download" />
                        </form>
                    </div>
                </div>
            <?php break;
                default:
            ?>
                <div class="wrap acf-settings-wrap">
                    <h2>Enews Form Entries</h1>
                        <p>Set the fields to generate downloadable data</p>

                        <div class='meta-box'>
                            <h3>All Enews Form Submissions (Detailed):</h3>
                            <form method="post" action="<?php echo $formAction ?>">
                                <input type="hidden" name="form-type" value="enews_form_all_detailed" />
                                <input type="submit" class="button" value="Generate & Download" />
                            </form>
                        </div>

                        <div class='meta-box'>
                            <h3>All Enews Form Submission Email Addresses:</h3>
                            <form method="post" action="<?php echo $formAction ?>">
                                <input type="hidden" name="form-type" value="enews_form_all" />
                                <input type="submit" class="button" value="Generate & Download" />
                            </form>
                        </div>

                        <div class='meta-box'>
                            <h3>Selected Enews Form Submissions:</h3>
                            <form method="post" action="<?php echo $formAction ?>">
                                <input type="hidden" name="form-type" value="enews_form_date_range" />
                                <label>
                                    Start Date:
                                    <input type="date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required>
                                </label>

                                <label>
                                    End Date:
                                    <input type="date" name="end_date" value="<?php echo date('Y-m-d'); ?>" required>
                                </label>
                                <input type="submit" class="button" value="Generate & Download" />
                            </form>
                        </div>
                </div>
        <?php break;
        endswitch; ?>
    </div>

</div>
