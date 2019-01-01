<?php

/**
 * @file
 * Contains dd_fax_service_history.page.inc.
 *
 * Page callback for Dd fax service history entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Dd fax service history templates.
 *
 * Default template: dd_fax_service_history.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_fax_service_history(array &$variables) {
  // Fetch DdFaxServiceHistory Entity Object.
  $dd_fax_service_history = $variables['elements']['#dd_fax_service_history'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
