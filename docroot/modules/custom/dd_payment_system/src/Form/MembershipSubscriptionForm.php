<?php
/**
 * @file
 * Contains \Drupal\dd_payment_system\Form\MembershipSubscriptionForm.
 */

namespace Drupal\dd_payment_system\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\dd_payment_system\Entity\DdSubscriptionPlan;
use Drupal\dd_payment_system\Utility\CommonHelper;

/**
 * Contribute form for annotating clip.
 */
class MembershipSubscriptionForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_payment_system_subscribe_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];
    $user_interface = $build_args['user_interface'];
    $plans = $build_args['plans'];

    $form['type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Confirm Membership Upgrade'),
      '#default_value' => CommonHelper::getUserPlan($user_interface, $plans),
      '#options' => CommonHelper::getPlanOptions($plans),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Subscribe',
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
    // Validate the form values.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    $curr_user = \Drupal::currentUser();
    $form_data = $form_state->getValues();
    $plans = DdSubscriptionPlan::loadByFields([
               ['field' => 'name', 'value' => $form_data['type']] 
             ]);
    $membership = current($plans);
    $node = Node::create(
      array(
        'type' => 'dd_invoice',
        'uid' => \Drupal::currentUser()->id(),
        'status' => 0,
        'title' => $form_data['type'],
        'language' => 'und',
      )
    );
    $duration = $membership->field_duration_days->first()->value;
    $node->field_balance_due->setValue($membership->field_price->first()->value);
    $node->field_total->setValue($membership->field_price->first()->value);
    $node->field_duration_days->setValue($duration);
    $node->field_expires
             ->setValue(CommonHelper::getExpirationDate($curr_user, $duration));
    $node->save(); 
    $url = $base_url . "/node/" . $node->id();
    $response = new RedirectResponse($url);
    $response->send();
  }
}
