<?php

/**
 * @file
 * Contains dd_legislative_staff.page.inc.
 *
 * Page callback for DD Legislative Staff entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Legislative Staff templates.
 *
 * Default template: dd_legislative_staff.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_legislative_staff(array &$variables) {
  // Fetch DdLegislativeStaff Entity Object.
  $dd_legislative_staff = $variables['elements']['#dd_legislative_staff'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
