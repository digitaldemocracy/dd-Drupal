<?php

namespace Drupal\dd_payment_system\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;


/**
 * Class DdPaymentSystemPlanSettingsForm.
 *
 * @package Drupal\dd_payment_system\Form
 *
 * @ingroup dd_payment_system
 */
class DdPaymentSystemSettingsForm extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'dd_payment_system_set_form';
  }

  protected function getEditableConfigNames() {
    return ['dd_payment_system.settings'];
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = \Drupal::configFactory()->getEditable('dd_payment_system.settings')
      ->set('stripe_name', $values['stripe_name'])
      ->set('stripe_description', $values['stripe_description'])
      ->set('stripe_zip_code', $values['stripe_zip_code'])
      ->set('stripe_image', $values['stripe_image'])
      ->set('default_alert_days', $values['alert_days'])
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Defines the settings form for DD Subscription Plan entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('dd_payment_system.settings');

    $form['stripe_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('The name of your company.'),
      '#default_value' => $config->get('stripe_name'),
    ];

    $form['stripe_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('A description of payment.'),
      '#default_value' => $config->get('stripe_description'),
    ];
    
    $form['stripe_zip_code'] = [
      '#type' => 'select',
      '#title' => $this->t('Show zip code.'),
      '#options' => [
        'true' => 'true',
        'false' => 'false',
      ],
      '#default_value' => $config->get('stripe_zip_code'),
    ];

    $form['stripe_image'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL to icon.'),
      '#default_value' => $config->get('stripe_image'),
    ];

    $form['alert_days'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of days to alert user of expiring account.'),
      '#default_value' => $config->get('default_alert_days'),
    ];

    return parent::buildForm($form, $form_state);
  }
}
