<?php

namespace Drupal\dd_payment_system\Controller;

use Stripe\Charge;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\stripe_api\StripeApiService;
use Drupal\user\Entity\User;
use Drupal\dd_payment_system\Utility\CommonHelper;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class StripeChargeController.
 *
 * @package Drupal\dd_payment_system\Controller
 */
class DdPaymentSystemController extends ControllerBase {
  /**
   * A function to handle request for membership subscripotion.
   *
   * @param Int $user
   *   user id
   *
   * @return renderable array
   *   renderable array for form 
   */
  public function upgrade($user) {
    $plans = CommonHelper::getPlans();
    $user_interface = User::load(\Drupal::currentUser()->id());
    $config = $this->config('dd_payment_system.settings');

    $days_to_allow = $config->get('default_alert_days');

    if ($user_interface->field_expiration_date->first()) {
      $exp_date =
        strtotime($user_interface->field_expiration_date->first()->value);
      $sys_date = strtotime("+" . $days_to_allow . " days");
      if ($exp_date > $sys_date) {
        return array(
          '#markup' =>
          '<p>Sorry. '
          . 'This operation is only available from ' . $days_to_allow . ' days prior to the expiration date of your current subscription: '
          . date('m/d/Y', $exp_date)
          . '.</p>',    
        );
      }
    }

    $build_args = array(
      'user_interface' => $user_interface,
      'back_link' => CommonHelper::getReferer(),
      'plans' => $plans,
    );
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_payment_system\Form\MembershipSubscriptionForm',
                $build_args);
    return array(
      '#theme' => 'dd-subscribe-plan',
      '#title' => '',
      '#form' => $form,
      '#items' => array('plans' => $plans),
    );
  }

  /**
   * A function to handle request for membership cancellation.
   *
   * @param Int $user
   *   user id
   *
   * @return renderable array
   *   renderable array for form 
   */
  public static function cancel($user) {
    $plans = CommonHelper::getPlans();
    $user_interface = User::load(\Drupal::currentUser()->id());
    $build_args = array(
      'user_interface' => $user_interface,
      'back_link' => CommonHelper::getReferer(),
    );

    if (!CommonHelper::getUserSubscribedPlan()) {
      return array(
        '#markup' =>
          'Please contact ' . 
          '<a href="https://www.digitaldemocracy.org/contact">us</a>' .
          ' to delete your account.',
        '#title' => 'Cancel Free Membership',);
    }

    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_payment_system\Form\MembershipCancelForm',
                $build_args);
    return array(
      '#theme' => 'dd-cancel-plan',
      '#title' => '',
      '#form' => $form,
      '#items' => array(
        'plan' => CommonHelper::getUserPlan($user_interface, $plans)),
    );
  }
}
