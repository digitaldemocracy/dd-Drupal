<?php

namespace Drupal\dd_payment_system\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\stripe_api\Event\StripeApiWebhookEvent;
use Drupal\stripe_api\StripeApiService;
use Stripe\Event;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DdPaymentSystemWebhook
 *
 * Provides the route functionality for dd_payment_system.stripe_webhook route.
 */
class DdPaymentSystemWebhook extends ControllerBase {

  // Fake ID from Stripe we can check against.
  const FAKE_EVENT_ID = 'evt_00000000000000';

  /**
   * @var \Drupal\stripe_api\StripeApiService*/
  protected $stripeApi;

  /**
   * {@inheritdoc}
   */
  public function __construct(StripeApiService $stripe_api) {
    $this->stripeApi = $stripe_api;
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
   * Captures the incoming webhook request.
   *
   * @param Request $request
   *
   * @return Response
   */
  public function handleIncomingWebhook(Request $request) {
    $input = $request->getContent();
    $decoded_input = json_decode($input);
    $config = $this->config('stripe_api.settings');
    $mode = $config->get('mode') ?: 'test';

    if ($decoded_input->type === 'customer.subscription.deleted') {
      $this->moduleHandler()->invokeAll('stripe_subscription_deleted', [
        $decoded_input->data->object->id,
      ]);
      return new Response('Okay', Response::HTTP_OK);
    } else if ($decoded_input->type === 'invoice.created') {
      $this->moduleHandler()->invokeAll('stripe_invoice_created', [
        $decoded_input->data->object->customer,
        $decoded_input->data->object->amount_due,
        $decoded_input->data->object->lines->data[0]->plan->name,
        $decoded_input->data->object->lines->data[0]->plan->interval,
        $decoded_input->data->object->lines->data[0]->plan->interval_count,
      ]);
      return new Response('Okay', Response::HTTP_OK);
    } else {
      return new Response('ERROR', Response::HTTP_FORBIDDEN);
    }
  }

  /**
   * Determines if a webhook is valid.
   *
   * @param string $mode
   *   Stripe API mode. Either 'live' or 'test'.
   * @param object $event_json
   *   Stripe event object parsed from JSON.
   *
   * @return bool|\Stripe\Event
   *   Returns TRUE if the webhook is valid or the Stripe Event object.
   */
  private function isValidWebhook($mode, $data) {
    if (!empty($data->id)) {

      if ($mode === 'live' && $data->livemode == TRUE
      || $mode === 'test' && $data->livemode == FALSE
      || $data->id == self::FAKE_EVENT_ID) {

        // Verify the event by fetching it from Stripe.
        return Event::retrieve($data->id);
      }
    }

    return FALSE;
  }
}
