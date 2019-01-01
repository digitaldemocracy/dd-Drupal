<?php

/**
 * @file
 * Contains dd_hearing.page.inc.
 *
 * Page callback for DD Hearing entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\dd_video\Entity\DdVideo;

/**
 * Prepares variables for DD Hearing templates.
 *
 * Default template: dd-hearing.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_hearing(array &$variables) {
  // Fetch DdHearing Entity Object.
  $dd_hearing = $variables['elements']['#dd_hearing'];

  $vids = DdVideo::getVideoFileIdsForHearingId($dd_hearing->id());

  $video_markup = array(
    '#type' => 'html_tag',
    '#tag' => 'div',
    '#attributes' => array(
      'class' => 'ddModule',
      'id' => 'dd-videos',
      'data-youtubeid' => implode(',', $vids),
      'data-videourlpath' => 'https://videostorage-us-west.s3.amazonaws.com/videos/',
    ),
  );

  $variables['video_markup'] = $video_markup;
  $variables['elements']['video_markup'] = $video_markup;

  // Add Share Toolbar.
  $block_manager = \Drupal::service('plugin.manager.block');
  $plugin_block = $block_manager->createInstance('dd_share_toolbar_block');
  $variables['dd_share_toolbar'] = $plugin_block->build();

  $variables['title'] = $dd_hearing->label();
}
