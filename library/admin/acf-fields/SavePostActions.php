<?php

class SavePostActions
{
    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        // add_action('acf/save_post', [$this, 'copyHeroImageToThumbnail'], 10);
        add_action('acf/save_post', [$this, 'copyContentToPostContent'], 10);
        add_action('acf/save_post', [$this, 'copyContentToPostExcerpt'], 10);
    }

    /**
     * Copy Hero Image ACF field to featured_thumbnail
     *
     * @param mixed $post_id
     * @return void
     */
    public function copyHeroImageToThumbnail($post_id)
    {
        global $post_type;

        if ($post_type == 'post') {
            return;
        }

        $imageID = get_field('hero_image', $post_id)['id'];
        if ($imageID) {
            wp_update_post([
                'ID' => $post_id,
                '_thumbnail_id' => $imageID
            ]);
        }
    }

    /**
     * Copy ACF field to post_content
     *
     * @param mixed $post_id
     * @return void
     */
    public function copyContentToPostContent($post_id)
    {
        global $post_type;

        $value = get_field('core_content', $post_id);

        if ($value) {
            wp_update_post([
                'ID' => $post_id,
                'post_content' => str_replace('**', '', $value)
            ]);
        }
    }

    /**
     * Copy ACF field to post_excerpt if empty
     *
     * @param mixed $post_id
     * @return void
     */
    public function copyContentToPostExcerpt($post_id)
    {
        global $post_type;

        $postExcerpt = $_POST['post_excerpt'];
        if ($postExcerpt != '') {
            return; // Don't overwrite once set
        }

        $value = get_field('core_excerpt', $post_id);

        if (empty($value)) {
            $value = preg_match('/^([^.!?]*[\.!?]+){0,2}/', strip_tags(get_field('core_intro', $post_id)), $firstTwoSentences);
            $value = $firstTwoSentences[0];
        }

        if ($value) {
            wp_update_post([
                'ID' => $post_id,
                'post_excerpt' => str_replace('**', '', $value)
            ]);
        }
    }
}

new SavePostActions();
