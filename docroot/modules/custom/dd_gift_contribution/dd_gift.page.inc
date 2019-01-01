<?php

/**
 * @file
 * Contains dd_gift.page.inc.
 *
 * Page callback for DD Gift entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Gift templates.
 *
 * Default template: dd_gift.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_gift(array &$variables) {
  // Fetch DdGift Entity Object.
  $dd_gift = $variables['elements']['#dd_gift'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
