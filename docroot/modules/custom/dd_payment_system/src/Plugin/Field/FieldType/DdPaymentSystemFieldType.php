<?php

namespace Drupal\dd_payment_system\Plugin\Field\FieldType;

use Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'stripe_checkout_field_type' field type.
 *
 * @FieldType(
 *   id = "dd_pay",
 *   label = @Translation("Dd Payment"),
 *   description = @Translation("Stripe Checkout field. Accepts decimal cost of a purchase as value."),
 *   category = @Translation("Number"),
 *   default_widget = "number",
 *   default_formatter = "dd_payment_system_formatter"
 * )
 */
class DdPaymentSystemFieldType extends DecimalItem {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return array(
      'currency' => 'usd',
    ) + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = parent::storageSettingsForm($form, $form_state, $has_data);
    $settings = $this->getSettings();

    $element['currency'] = array(
      '#type' => 'textfield',
      '#title' => t('Currency'),
      '#default_value' => $settings['currency'],
      '#length' => 3,
      '#size' => 3,
      '#description' => t('The three character ISO currency code for this price.'),
      '#disabled' => $has_data,
    );

    return $element;
  }

}
