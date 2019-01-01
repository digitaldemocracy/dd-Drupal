<?php

/**
 * @file
 * Contains dd_video_tags.page.inc.
 *
 * Page callback for DD Video Tags entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Video Tags templates.
 *
 * Default template: dd_video_tags.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_video_tags(array &$variables) {
  // Fetch DdVideoTags Entity Object.
  $dd_video_tags = $variables['elements']['#dd_video_tags'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
