<?php

namespace Drupal\dd_admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DdAdminMemberSettings.
 *
 * @package Drupal\dd_admin\Form
 */
class DdAdminMemberSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dd_admin.DdAdminMemberSettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_admin_member_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_admin.DdAdminMemberSettings');
    $form['enable_member_accounts'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Member Accounts'),
      '#description' => $this->t('Toggles user registration/login features'),
      '#default_value' => $config->get('enable_member_accounts'),
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
    $config = $this->configFactory->getEditable('dd_admin.DdAdminMemberSettings');
    $form_keys = [
      'enable_member_accounts',
    ];
    foreach ($form_keys as $form_key) {
      $config->set($form_key, $form_state->getValue($form_key));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
