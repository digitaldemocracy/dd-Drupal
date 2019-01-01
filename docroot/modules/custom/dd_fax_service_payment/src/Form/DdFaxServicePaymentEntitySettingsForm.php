<?php

namespace Drupal\dd_fax_service_payment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DdFaxServicePaymentEntitySettingsForm.
 *
 * @package Drupal\dd_fax_service_payment\Form
 *
 * @ingroup dd_fax_service_payment
 */
class DdFaxServicePaymentEntitySettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'DdFaxServicePaymentEntity_settings';
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
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Dd fax service payment entity entities.
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
    $form['DdFaxServicePaymentEntity_settings']['#markup'] = 'Settings form for Dd fax service payment entity entities. Manage field settings here.';
    return $form;
  }

}
