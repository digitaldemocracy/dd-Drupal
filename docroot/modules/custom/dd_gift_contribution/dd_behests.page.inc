<?php

/**
 * @file
 * Contains dd_behests.page.inc.
 *
 * Page callback for DD Behests entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Behests templates.
 *
 * Default template: dd_behests.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_behests(array &$variables) {
  // Fetch DdBehests Entity Object.
  $dd_behests = $variables['elements']['#dd_behests'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
