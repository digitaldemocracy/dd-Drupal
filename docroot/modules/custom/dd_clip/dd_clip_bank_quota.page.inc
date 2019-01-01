<?php

/**
 * @file
 * Contains dd_clip_bank_quota.page.inc.
 *
 * Page callback for DD Clip Bank Quota entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for DD Clip Bank Quota templates.
 *
 * Default template: dd_clip_bank_quota.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_clip_bank_quota(array &$variables) {
  // Fetch DdClipBankQuota Entity Object.
  $dd_clip_bank_quota = $variables['elements']['#dd_clip_bank_quota'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
