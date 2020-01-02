<?php

/**
 * save_thumbnail
 * Save hero or slider as featured image for social media
 *
 * @param  mixed $post_id The ID of the post to create the thumb on
 *
 * @return void
 */

function save_thumbnail($post_id)
{
    // If this is just a revision, return
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // dd(new TimberPost($post_id));
    $main_thumbnail = NULL;

    if (get_field('field_news_image', $post_id)) {
        $main_thumbnail = get_field('field_news_image', $post_id, false);
    }
    /**else if (get_field('hero_thin_image', $post_id)) {
  $main_thumbnail = get_field('hero_thin_image', $post_id, false);
  } else if (get_field('article_hero_image', $post_id)) {
  $main_thumbnail = get_field('article_hero_image', $post_id, false);
  } else if (get_field('itinerary_hero_image', $post_id)) {
  $main_thumbnail = get_field('itinerary_hero_image', $post_id, false);
  } else if (get_field('property_hero_slides', $post_id) && count(get_field('property_hero_slides', $post_id)) > 0) {
  $main_thumbnail = get_field('property_hero_slides', $post_id, false)[0]['field_property_hero_image'];
  } else if (get_field('home_hero_slides', $post_id) && count(get_field('home_hero_slides', $post_id)) > 0) {
  $main_thumbnail = get_field('home_hero_slides', $post_id, false)[0]['field_home_hero_image'];
  }**/

    if (!has_post_thumbnail($post_id) && $main_thumbnail) {
        set_post_thumbnail($post_id, $main_thumbnail);
    }
}

add_action('save_post', 'save_thumbnail');
