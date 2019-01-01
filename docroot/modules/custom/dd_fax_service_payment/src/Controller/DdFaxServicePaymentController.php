<?php

namespace Drupal\dd_fax_service_payment\Controller;

use Stripe\Charge;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\stripe_api\StripeApiService;
use Drupal\user\Entity\User;
use Drupal\dd_payment_system\Utility\CommonHelper;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class DdFaxServicePaymentController.
 *
 * @package Drupal\dd_fax_service_payment\Controller
 */
class DdFaxServicePaymentController extends ControllerBase {

  /**
   * Builds the fax form.
   *
   * @param User $user
   *   user information
   *
   * @return Array
   *   Render array for the form.
   */
  public function extend_fax($user) {
    $fax_options = CommonHelper::getFaxPaymentOptions();
    $user_interface = User::load(\Drupal::currentUser()->id());

    $build_args = array(
      'user_interface' => $user_interface,
      'back_link' => CommonHelper::getReferer(),
      'fax_options' => $fax_options,
    );

    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_fax_service_payment\Form\FaxServicePaymentForm',
                $build_args);
    return array(
      '#theme' => 'dd-fax-service-payment-form',
      '#title' => '',
      '#form' => $form,
    );
  }
}
