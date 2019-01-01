<?php

namespace Drupal\dd_admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DdAdminEmailSettings.
 *
 * @package Drupal\dd_admin\Form
 */
class DdAdminEmailSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dd_admin.DdAdminEmailSettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_admin_email_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_admin.DdAdminEmailSettings');
    $form['enable_member_created_email_alerts'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Member Created Email Alerts'),
      '#default_value' => $config->get('enable_member_created_email_alerts'),
    ];
    $form['bcc_address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Send copy of sent emails to this address as bcc'),
      '#default_value' => $config->get('bcc_address'),
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
    $config = $this->configFactory->getEditable('dd_admin.DdAdminEmailSettings');
    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
