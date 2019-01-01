<?php

/**
 * @file
 * Contains \Drupal\metatag_views\Plugin\views\display_extender\MetatagDisplayExtender.
 */

namespace Drupal\metatag_views\Plugin\views\display_extender;

use Drupal\Core\Language\LanguageInterface;
use Drupal\metatag\MetatagManagerInterface;
use Drupal\metatag_views\MetatagViewsValuesCleanerTrait;
use Drupal\views\Plugin\views\display_extender\DisplayExtenderPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Metatag display extender plugin.
 *
 * @ingroup views_display_extender_plugins
 *
 * @ViewsDisplayExtender(
 *   id = "metatag_display_extender",
 *   title = @Translation("Metatag display extender"),
 *   help = @Translation("Metatag settings for this view."),
 *   no_ui = FALSE
 * )
 */
class MetatagDisplayExtender extends DisplayExtenderPluginBase {
  use MetatagViewsValuesCleanerTrait;
  /** @var  MetatagManagerInterface */
  protected $metatagManager;

  /**
   * Provide a form to edit options for this plugin.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {

    if ($form_state->get('section') == 'metatags') {
      $form['#title'] .= t('The meta tags for this display');
      $metatags = $this->getMetatags();
      $metatags = !empty($metatags) ? $metatags[LanguageInterface::LANGCODE_NOT_SPECIFIED] : array();

      // Build/inject the Metatag form.
      $this->metatagManager = \Drupal::service('metatag.manager');
      $form['metatags'] = $this->metatagManager->form( $metatags, $form, [ 'view' ] );
    }
  }

  /**
   * Validate the options form.
   */
  public function validateOptionsForm(&$form, FormStateInterface $form_state) {
  }

  /**
   * Handle any special handling on the validate form.
   */
  public function submitOptionsForm(&$form, FormStateInterface $form_state) {
    if ($form_state->get('section') == 'metatags') {
      $metatags = $form_state->getValues();
      // Remove the unnecessary elements from values array.
      $metatags = $this->clearMetatagViewsDisallowedValues($metatags);
      $this->options['metatags'] = $metatags;
    }
  }

  /**
   * Set up any variables on the view prior to execution.
   */
  public function preExecute() {
  }

  /**
   * Inject anything into the query that the display_extender handler needs.
   */
  public function query() {
  }

  /**
   * Provide the default summary for options in the views UI.
   *
   * This output is returned as an array.
   */
  public function optionsSummary(&$categories, &$options) {
    $categories['metatags'] = array(
      'title' => t('Meta tags'),
      'column' => 'second',
    );
    $options['metatags'] = array(
      'category' => 'metatags',
      'title' => t('Meta tags'),
      'value' => $this->hasMetatags() ? t('Overridden') : t('Using defaults'),
    );
  }

  /**
   * Static member function to list which sections are defaultable
   * and what items each section contains.
   */
  public function defaultableSections(&$sections, $section = NULL) {
  }

  /**
   * Identify whether or not the current display has custom meta tags defined.
   */
  protected function hasMetatags() {
    $metatags = $this->getMetatags();
    return !empty($metatags[LanguageInterface::LANGCODE_NOT_SPECIFIED]);

  }

  /**
   * Get the Metatag configuration for this display.
   *
   * @return array
   *   The meta tag values, keys by language (default LanguageInterface::LANGCODE_NOT_SPECIFIED).
   */
  public function getMetatags($language = NULL) {
    if (isset($this->options['metatags'])) {
      $metatags = $this->options['metatags'];
    }

    $language = is_null($language) ? LanguageInterface::LANGCODE_NOT_SPECIFIED : $language;
    // Leave some possibility for future versions to support translation.
    if (empty($metatags)) {
      $metatags = array($language => array());
    }
    if (!isset($metatags[$language])) {
      $metatags = array($language => $metatags);
    }

    return $metatags;
  }

  public function setMetatags($metatags) {
    $this->options['metatags'] = $metatags;
  }
}
