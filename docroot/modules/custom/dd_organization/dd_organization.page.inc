<?php

/**
 * @file
 * Contains dd_organization.page.inc.
 *
 * Page callback for DD Organization entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Organization templates.
 *
 * Default template: dd_organization.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_organization(array &$variables) {
  // Fetch DdOrganization Entity Object.
  $dd_organization = $variables['elements']['#dd_organization'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
