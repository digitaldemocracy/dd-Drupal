<?php
/**
 * @file
 * Contains \Drupal\dd_payment_system\Form\MembershipCancelForm.
 */

namespace Drupal\dd_payment_system\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\node\Entity\Node;
use \Drupal\dd_payment_system\Utility\CommonHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\user\Entity\User;

/**
 * Contribute form for annotating clip.
 */
class MembershipCancelForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dd_payment_system_cancel_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $build_args = $form_state->getBuildInfo()['args'][0];

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Yes',
    );

    $form['cancel'] = array(
      '#markup' => '<a href="' . $build_args['back_link'] . '">No</a>',
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
    $form_data = $form_state->getValues();
    $curr_user = \Drupal::currentUser();
    $user_interface = User::load($curr_user->id());

    $pub_key = \Drupal::service('stripe_api.stripe_api')->getPubKey();
    $sid = $user_interface->field_sku->value;
    $sub = \Stripe\Subscription::retrieve($sid);
    $sub->cancel(array('at_period_end' => true));

    $expiration = $user_interface->field_expiration_date->first()->value;
    $exp_date = strtotime($expiration);
    $plan = CommonHelper::getUserPlan($user_interface,
                                      CommonHelper::getPlans());
    drupal_set_message('Your subscription to ' . $plan . ' will be canceled on '
      . date('m/d/Y', $exp_date) . '. Thank you.');
    $url = $base_url . "/user/" . $curr_user->id() . "/my-account";
    $response = new RedirectResponse($url);
    $response->send();
  }
}

