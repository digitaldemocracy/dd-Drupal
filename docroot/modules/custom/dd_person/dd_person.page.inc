<?php

/**
 * @file
 * Contains dd_person.page.inc.
 *
 * Page callback for DD Person entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Person templates.
 *
 * Default template: dd_person.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_person(array &$variables) {
  // Fetch DdPerson Entity Object.
  $dd_person = $variables['elements']['#dd_person'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
