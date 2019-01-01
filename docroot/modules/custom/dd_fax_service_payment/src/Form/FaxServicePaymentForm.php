<?php
/**
 * @file
 * Contains \Drupal\dd_fax_service_payment\Form\FaxServicePaymentForm.
 */

namespace Drupal\dd_fax_service_payment\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity;
use Drupal\dd_payment_system\Utility\CommonHelper;

class FaxServicePaymentForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_fax_service_payment_fax_service_payment_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];
    $user_interface = $build_args['user_interface'];

    $form['type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Purchase Fax Limit Extension'),
      '#options' => CommonHelper::getFaxPaymentRadios($build_args['fax_options']),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Purchase',
    );

    $form['cancel'] = array(
      '#markup' => '<a href="' . $build_args['back_link'] . '">Cancel</a>',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate form values.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;

    $user = \Drupal::currentUser();
    $form_data = $form_state->getValues();
    $fax_options = DdFaxServicePaymentEntity::loadByFields([
      ['field' => 'name', 'value' => $form_data['type']]
    ]);
    $fax_option = current($fax_options);
    $node = Node::create(
      array(
        'type' => 'dd_invoice',
        'uid' => \Drupal::currentUser()->id(),
        'status' => 0,
        'title' => $form_data['type'],
        'language' => 'und',
      )
    );

    $price = $fax_option->field_price->first()->value;
    $duration = $fax_option->field_duration->first()->value;

    $node->field_balance_due->setValue($price);
    $node->field_total->setValue($price);
    $node->field_duration_days->setValue($duration);
    $node->field_expires
      ->setValue(CommonHelper::getExpirationDate($user, $duration));

    $node->save();
    $url = $base_url . "/node/" . $node->id();
    $response = new RedirectResponse($url);
    $response->send();
  }
}
