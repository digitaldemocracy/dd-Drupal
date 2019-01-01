<?php

/**
 * @file
 * Contains dd_fax_service_payment_entity.page.inc.
 *
 * Page callback for Dd fax service payment entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Dd fax service payment entity templates.
 *
 * Default template: dd_fax_service_payment_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_fax_service_payment_entity(array &$variables) {
  // Fetch DdFaxServicePaymentEntity Entity Object.
  $dd_fax_service_payment_entity = $variables['elements']['#dd_fax_service_payment_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
