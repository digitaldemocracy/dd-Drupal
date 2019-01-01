<?php

namespace Drupal\dd_payment_system\Plugin\Field\FieldFormatter;

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
 *   id = "dd_payment_system_formatter",
 *   label = @Translation("Stripe checkout"),
 *   field_types = {
 *     "dd_pay"
 *   }
 * )
 */
class DdPaymentSystemFormatter extends FormatterBase {

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
      //Implement default settings.
    ] + parent::defaultSettings();
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
    $config = \Drupal::config('dd_payment_system.settings');
    $user = \Drupal::currentUser();
    
    if (empty($pub_key)) {
      \Drupal::logger('dd_payment_system')->error(t('The public key is incorrect.'));
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
          'name' => $config->get('stripe_name'),
          'description' => $config->get('stripe_description'),
          'key' => $pub_key,
          'zip_code' => $config->get('stripe_zip_code'),
          'locale' => 'auto',
          'image' => $config->get('stripe_image'),
          'email' => $user->getEmail(),
          'label' => $this->t('Purchase'),
        ],
        '#price' => $price,
        '#entity_id' => $item->getEntity()->id(),
        '#field_name' => $item->getFieldDefinition()->getName(),
        '#logged_in' => $user->isAuthenticated(),
        "#anon_url" => Url::fromRoute('user.register', [], [
          'query' => [
            'destination' => $current_path,
            'stripe_checkout_click' => TRUE,
          ],
        ]),
        '#action' => Url::fromRoute('dd_payment_system.stripe_charge'),
        '#attached' => [
          'library' => [
            'dd_payment_system/checkout',
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
