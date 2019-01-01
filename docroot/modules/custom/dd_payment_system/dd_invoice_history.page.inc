<?php

/**
 * @file
 * Contains dd_invoice_history.page.inc.
 *
 * Page callback for Dd invoice history entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Dd invoice history templates.
 *
 * Default template: dd_invoice_history.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_invoice_history(array &$variables) {
  // Fetch DdInvoiceHistory Entity Object.
  $dd_invoice_history = $variables['elements']['#dd_invoice_history'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
