<?php

namespace Drupal\dd_payment_system\Controller;

use Stripe\Charge;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\stripe_api\StripeApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Drupal\dd_payment_system\Entity\DdSubscriptionPlan;
use \Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity;
use \Drupal\user\Entity\User;
/**
 *
 * @package Drupal\dd_payment_system\Controller
 */
class DdPaymentSystemStripeController extends ControllerBase {

  /**
   * Drupal\stripe_api\StripeApiService definition.
   *
   * @var \Drupal\stripe_api\StripeApiService
   */
  protected $stripeApiStripeApi;

  /**
   * {@inheritdoc}
   */
  public function __construct(StripeApiService $stripe_api_stripe_api) {
    $this->stripeApiStripeApi = $stripe_api_stripe_api;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('stripe_api.stripe_api')
    );
  }

  /**
   * Helper function for charge that updates or creates subscriptions for
   * customers.
   *
   * @param AccountInterface $user
   *
   * @param \Stripe\Customer $customer
   *
   * @param \Drupal\node\Entity\Node $node
   */
  private function stripe_add_subscription($user, $customer, $node) {
    $field_sub = $user->field_sku->value;
    $plans = DdSubscriptionPlan::loadByFields(
      [['field' => 'name', 'value' => $node->getTitle()]]);
    $plan_name = current($plans)->field_role_name->first()->value;

    if ($field_sub) {
      $subscription = \Stripe\Subscription::retrieve($field_sub);
      $subscription->plan = $plan_name;
      $subscription->prorate = false;
      $subscription->save;

      return NULL;
    } else {
      try{
        $subscription = \Stripe\Subscription::create(array(
          "customer" => $customer->id,
          "plan" => $plan_name,
        ));

      return $subscription;
      } catch (Exception $e) {
        $this->getLogger('dd_payment_system.logger')->error($e->getMessage());
      }

    }
  }

  /**
   * Charge.
   *
   * @return string
   *   Return Hello string.
   */
  public function charge(Request $request) {
    $subscription = NULL;
    try {
      $token = $request->get('stripeToken');
      $entity_id = $request->get('entity_id');
      $field_name = $request->get('field_name');

      if (!$token || !$entity_id || !$field_name) {
        throw new \Exception("Required data is missing!");
      }

      // Load the price from the node that was purchased.
      $node = $this->entityTypeManager()->getStorage('node')->load($entity_id);
      $amount = $node->$field_name->value;
      $field_settings = $node->$field_name->getSettings();
      $currency = $field_settings['currency'];

      $user = \Drupal::currentUser()->getAccount();
      $user_interface = User::load($user->id()); 
      $user_email = $user_interface->getEmail();
      $customer_created = false;

      // Get customer. If no customer exists, create customer.
      if ($cid = $user_interface->field_cid->value) {
        $customer = \Stripe\Customer::retrieve($cid);
      } else {
        $customer = \Stripe\Customer::create(array(
          "description" => $this->t('Customer for @email', ['@email' => $user_email]),
          "email" => $user_email,
          "source" => $token,
        ));
        $customer_created = true;
      }

      // Check if plan or charge. Check if plan is recurring.
      $plans = DdSubscriptionPlan::loadByFields(
        [['field' => 'name', 'value' => $node->getTitle()]]
      );

      $fax_payment = DdFaxServicePaymentEntity::loadByFields(
        [['field' => 'name', 'value' => $node->getTitle()]]
      );

      if (!empty($plans)) {
        // Check if plan is recurring.
        if (current($plans)->field_recurring->first()->value) {
          // Add Subscription to customer.
          $subscription = $this->stripe_add_subscription($user_interface, $customer, $node);
        } else {
          // Otherwise create one time charge.
          $charge = Charge::create(array(
            //Convert to ccents.
            "amount" => $amount * 100,
            "customer" => $customer,
            "currency" => $currency,
            "metadata" => [
              'entity_id' => $entity_id,
              'uid' => $user->id(),
            ],
          ));
        }
      } else {
        // Create one time charge.
        $charge = Charge::create(array(
          //Convert to cents.
          "amount" => $amount * 100,
          "customer" => $customer,
          "currency" => $currency,
          "metadata" => [
            'entity_id' => $entity_id,
            'uid' => $user->id(),
          ],
        ));
      }
      
      if ($customer !== NULL) {
        drupal_set_message(t("Thank you. Your payment has been processed."));

        // At this point a Stripe webhook should make a request to the stripe_api.webhook route, which will dispatch an event
        // to which event subscribers can react.
        $this->getLogger('dd_payment_system.logger')->info(t("Successfully processed Stripe charge. This should have triggered a Stripe webhook. \nsubmitted data:@data", [
          '@data' => $request->getContent(),
        ]));

        // In addition to the webhook, we fire a traditional Drupal hook to permit other modules to respond to this event instantaneously.
        if (!empty($fax_payment)) {
          $this->moduleHandler()->invokeAll('stripe_charge_succeeded_fax', [
            $customer_created,
            $user_interface,
            $customer,
            $node,
            current($fax_payment)
          ]);
        } else {
          $this->moduleHandler()->invokeAll('stripe_charge_succeeded', [
            $customer_created,
            $user_interface,
            $customer,
            $subscription,
            $node
          ]);
        }
        
        return $this->redirect('entity.node.canonical', array('node' => $entity_id));
      }
      else {
        drupal_set_message(t("Payment failed."), 'error');

        $this->getLogger('dd_payment_system.logger')->error(t("Could not complete Stripe charge. \nsubmitted data:@data", [
          '@data' => $request->getContent(),
        ]));

        return new Response(NULL, Response::HTTP_FORBIDDEN);
      }

    }
    catch (\Exception $e) {
      drupal_set_message(t("Payment failed."), 'error');

      $this->getLogger('dd_payment.logger')->error(t("Could not complete Stripe charge, error:\n@error\nsubmitted data:@data", [
        '@data' => $request->getContent(),
        '@error' => $e->getMessage(),
      ]));
      return new Response(NULL, Response::HTTP_FORBIDDEN);
    }
  }
}
