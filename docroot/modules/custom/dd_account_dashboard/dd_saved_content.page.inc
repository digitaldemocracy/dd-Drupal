<?php

/**
 * @file
 * Contains dd_saved_content.page.inc.
 *
 * Page callback for DD Saved Content entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Saved Content templates.
 *
 * Default template: dd_saved_content.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_saved_content(array &$variables) {
  // Fetch DdSavedContent Entity Object.
  $dd_saved_content = $variables['elements']['#dd_saved_content'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
