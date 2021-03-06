<?php

namespace Drupal\config_ignore\Form;

use Drupal\config_ignore\ConfigImporterIgnore;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides a setting UI for Config Ignore.
 *
 * @package Drupal\config_ignore\Form
 */
class Settings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'config_ignore.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_ignore_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

    $config_ignore_settings = $this->config('config_ignore.settings');
    $form['ignored_config_entities'] = [
      '#type' => 'textarea',
      '#rows' => 25,
      '#title' => $this->t('Configuration entity names to ignore'),
      '#description' => $this->t('One configuration name per line.<br />Examples: <ul><li>user.settings</li><li>views.settings</li><li>contact.settings</li><li>webform.webform.* (will ignore all config entities that starts with <em>webform.webform</em>)</li><li>* (will ignore everything)</li><li>@force_importwebform.webform.contact (will force import for this configuration, even if ignored by a wildcard)</li></ul>', ['@force_import' => ConfigImporterIgnore::FORCE_EXCLUSION_PREFIX]),
      '#default_value' => implode(PHP_EOL, $config_ignore_settings->get('ignored_config_entities')),
      '#size' => 60,
    ];
    $form['ignored_regions'] = [
      '#type' => 'textarea',
      '#rows' => 25,
      '#title' => $this->t('Block Layout Regions to ignore'),
      '#description' => $this->t('One region name per line.<br />Examples: <ul><li>content</li><li>breadcrumb</li><li>sidebar* (will ignore all regions that start with <em>sidebar</em>)<li>@force_importsidebar_first (will force import for sidebar_first region, even if ignored by a wildcard)</li></ul>', ['@force_import' => ConfigImporterIgnore::FORCE_EXCLUSION_PREFIX]),
      '#default_value' => implode(PHP_EOL, $config_ignore_settings->get('ignored_regions')),
      '#size' => 60,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config_ignore_settings = $this->config('config_ignore.settings');
    $config_ignore_settings_array = preg_split("[\n|\r]", $values['ignored_config_entities']);
    $config_ignore_settings_array = array_filter($config_ignore_settings_array);
    $config_ignore_settings->set('ignored_config_entities', $config_ignore_settings_array);

    $config_ignore_region_settings_array = preg_split("[\n|\r]", $values['ignored_regions']);
    $config_ignore_region_settings_array = array_filter($config_ignore_region_settings_array);
    $config_ignore_settings->set('ignored_regions', $config_ignore_region_settings_array);

    $config_ignore_settings->save();
    parent::submitForm($form, $form_state);
  }

}
