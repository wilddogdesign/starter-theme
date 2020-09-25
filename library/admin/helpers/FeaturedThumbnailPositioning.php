<?php

if (!defined('ABSPATH')) {
    exit;
}

class FeaturedThumbnailPositioning
{
    public function __construct()
    {
        /* Hooks all required actions by this class */
        add_filter('admin_post_thumbnail_html', array($this, 'supportingText'), 10, 3);
        add_filter('admin_post_thumbnail_html', array($this, 'imagePositioningDrowndownField'), 10, 3);
        add_action('save_post', array($this, 'saveValue'), 999, 3);
    }

    // WS Specific
    public function supportingText($content, $post_id, $thumbnail_id)
    {
        $post = get_post($post_id);

        switch ($post->post_type) {
            default:
                $content = '<i>This image is used for SEO purposes</i>' . $content;
                return $content;
                break;
        }
        return $content;
    }

    public function imagePositioningDrowndownField($content, $post_id, $thumbnail_id)
    {
        $thumbnail_default_position = get_field('autop__image_position', $thumbnail_id) ? get_field('autop__image_position', $thumbnail_id) : 'center';
        $existing_position = get_field('thumbnail_position', $post_id);

        $top = false;
        $bottom = false;
        $left = false;
        $right = false;
        $center = false;


        if ($existing_position) {
            switch ($existing_position) {
                case 'top':
                    $top = 'selected';
                    break;
                case 'bottom':
                    $bottom = 'selected';
                    break;
                case 'left':
                    $left = 'selected';
                    break;
                case 'right':
                    $right = 'selected';
                    break;
                default:
                    $center = 'selected';
                    break;
            }
        } else {
            switch ($thumbnail_default_position) {
                case 'top':
                    $top = 'selected';
                    break;
                case 'bottom':
                    $bottom = 'selected';
                    break;
                case 'left':
                    $left = 'selected';
                    break;
                case 'right':
                    $right = 'selected';
                    break;
                default:
                    $center = 'selected';
                    break;
            }
        }

        $html = "
            <label for='thumbnail_positioning' style='margin: 10px 0; display: block;' ><strong>Select Focal Point:</strong></label>

            <select name='thumbnail_position' id='thumbnail_positioning'>
                <option value='center' $center>Center</option>
                <option value='top' $top>Top</option>
                <option value='bottom' $bottom>Bottom</option>
                <option value='left' $left>Left</option>
                <option value='right' $right>Right</option>
            </select>
        ";

        $content = $content . $html;

        return $content;
    }

    public function saveValue($post_id, $post, $update)
    {
        $thumbnail_postitioning_value = false;

        if (isset($_POST['_thumbnail_id'])) {
            if (isset($_POST['thumbnail_position'])) {
                $thumbnail_postitioning_value = $_POST['thumbnail_position'];
            }
            update_post_meta($post_id, 'thumbnail_position', $thumbnail_postitioning_value);
        };
    }
}

new FeaturedThumbnailPositioning();
