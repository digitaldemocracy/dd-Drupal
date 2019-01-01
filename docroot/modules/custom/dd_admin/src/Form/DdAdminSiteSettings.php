<?php

namespace Drupal\dd_admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DdAdminSiteSettings.
 *
 * @package Drupal\dd_admin\Form
 */
class DdAdminSiteSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dd_admin.DdAdminSiteSettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_admin_site_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_admin.DdAdminSiteSettings');
    $form['social'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Social Media'),
    ];
    $form['social']['social_facebook_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook URL'),
      '#description' => $this->t('Your Facebook URL'),
      '#default_value' => $config->get('social_facebook_url'),
    ];
    $form['social']['social_twitter_account'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Account'),
      '#description' => $this->t('Twitter account (without @, such as &quot;AssemblyGOP&quot;)'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('social_twitter_account'),
    ];
    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General Settings'),
    ];
    $form['general']['default_session_year'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Filter Session Year by Default'),
      '#description' => $this->t('If enabled, bills and hearings pages will filter to current session year by default.'),
      '#default_value'=> $config->get('default_session_year'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('dd_admin.DdAdminSiteSettings');
    $form_keys = [
      'default_session_year',
      'social_facebook_url',
      'social_twitter_account',
    ];
    foreach ($form_keys as $form_key) {
      $config->set($form_key, $form_state->getValue($form_key));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }
}
