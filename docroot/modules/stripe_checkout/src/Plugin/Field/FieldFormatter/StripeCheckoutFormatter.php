<?php

namespace Drupal\stripe_checkout\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'stripe_checkout_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "stripe_checkout_formatter",
 *   label = @Translation("Stripe checkout"),
 *   field_types = {
 *     "stripe_checkout"
 *   }
 * )
 */
class StripeCheckoutFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $pub_key = \Drupal::service('stripe_api.stripe_api')->getPubKey();
    $current_path = Url::fromRoute('<current>')->getInternalPath();
    
    if (empty($pub_key)) {
      // @todo Log an error.
      return FALSE;
    }
    
    foreach ($items as $delta => $item) {
      // @todo add cacheability metadata for api key and entity.
      $price = $this->viewValue($item);

      $elements[$delta] = [
        '#theme' => 'stripe_checkout',
        '#data' => [
          // Price is specified in cents.
          'amount' => $price * 100,
          // @todo Make configurable.
          'name' => 'Digital Democracy',
          // @todo Make configurable.
          'description' => 'Premium Membership Fee',
          'key' => $pub_key,
          // @todo Make configurable.
          'zip_code' => 'true',
          'locale' => 'auto',
          // @todo Make configurable.
          'image' => 'https://s3-us-west-2.amazonaws.com/dd-drupal-files/images/icons/dd_mini_logo.jpg',
          'email' => 'cadigitaldemocracy@gmail.com',
          'label' => $this->t('Purchase'),
        ],
        '#price' => $price,
        '#entity_id' => $item->getEntity()->id(),
        '#field_name' => $item->getFieldDefinition()->getName(),
        '#logged_in' => \Drupal::currentUser()->isAuthenticated(),
        "#anon_url" => Url::fromRoute('user.register', [], [
          'query' => [
            'destination' => $current_path,
            'stripe_checkout_click' => TRUE,
          ],
        ]),
        '#action' => Url::fromRoute('stripe_checkout.stripe_charge_controller_charge'),
        '#attached' => [
          'library' => [
            'stripe_checkout/checkout',
          ],
        ],
      ];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
