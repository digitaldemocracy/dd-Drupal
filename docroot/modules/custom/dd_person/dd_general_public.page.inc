<?php

/**
 * @file
 * Contains dd_general_public.page.inc.
 *
 * Page callback for DD General Public entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD General Public templates.
 *
 * Default template: dd_general_public.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_general_public(array &$variables) {
  // Fetch DdGeneralPublic Entity Object.
  $dd_general_public = $variables['elements']['#dd_general_public'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
