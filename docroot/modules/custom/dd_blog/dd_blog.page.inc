<?php

/**
 * @file
 * Contains dd_blog.page.inc.
 *
 * Page callback for blog edit/add form.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for node blog form templates.
 *
 * Default template: node-blog-form.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_node_blog_form(array &$variables) {
  dd_blog_prepare_render_elements($variables);
}

/**
 * Prepares variables for node blog form templates.
 *
 * Default template: node-blog-edit-form.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_node_blog_edit_form(array &$variables) {
  dd_blog_prepare_render_elements($variables);
}

/**
 * Prepares variables for node blog edit and add form templates.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function dd_blog_prepare_render_elements(array &$variables) {
  // Fetch DdPerson Entity Object.
  $variables['elements']['videoid'] = $variables['form']['videoid']['#value'];
  $variables['elements']['thumbnail'] = $variables['form']['thumbnail']['#value'];
  $variables['elements']['s3url'] = $variables['form']['s3url']['#value'];
  $variables['elements']['duration'] = $variables['form']['duration']['#value'];
  $variables['elements']['start_min'] = $variables['form']['start_min']['#value'];
  $variables['elements']['start_sec'] = $variables['form']['start_sec']['#value'];
  $variables['elements']['end_min'] = $variables['form']['end_min']['#value'];
  $variables['elements']['end_sec'] = $variables['form']['end_sec']['#value'];
}
