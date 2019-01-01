<?php

/**
 * @file
 * Contains dd_video.page.inc.
 *
 * Page callback for DD Video entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Video templates.
 *
 * Default template: dd_video.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_video(array &$variables) {
  // Fetch DdVideo Entity Object.
  $dd_video = $variables['elements']['#dd_video'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
