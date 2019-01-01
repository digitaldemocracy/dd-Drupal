<?php

namespace Drupal\dd_admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_fax_service\Utility\CommonHelper;
use Drupal\Core\Url;

/**
 * Class DdAdminFaxSettings.
 *
 * @package Drupal\dd_admin\Form
 */
class DdAdminFaxSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dd_admin.DdAdminFaxSettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_admin_fax_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dd_admin.DdAdminFaxSettings');
    $fax_history = CommonHelper::getFaxServiceHistory();
    $form['fax_limit'] = [
      '#type' => 'item',
      '#title' => $this->t('Fax Current Limit'),
      '#description' => $this->t($fax_history->field_current_limit->value),
    ];
    $form['faxes_sent'] = [
      '#type' => 'item',
      '#title' => $this->t('Number of Faxes Sent'),
      '#description' => $this->t($fax_history->field_faxes_sent->value), 
    ];
    $form['reset_date'] = [
      '#type' => 'item',
      '#title' => $this->t('Fax Limit Reset Date'),
      '#description' => $this->t($fax_history->field_end_date->value),
    ];
    $form['link_to_fax_payment'] = [
      '#title' => $this->t('<b>Increase Fax Limit</b>'),
      '#type' => 'link',
      '#url' => Url::fromRoute('dd_fax_service_payment.pay', 
        ['user' => \Drupal::currentUser()->id()]),
    ];
    return $form;
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
  }

}
