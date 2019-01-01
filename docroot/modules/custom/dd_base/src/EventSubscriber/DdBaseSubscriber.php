<?php

namespace Drupal\dd_base\EventSubscriber;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Site\Settings;
use Drupal\dd_base\DdBase;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DdBaseSubscriber implements EventSubscriberInterface {

  /**
   * Check if site should be redirected based on state.
   *
   * @param GetResponseEvent $event
   *   Event
   */
  public function checkForRedirection(\Symfony\Component\HttpKernel\Event\GetResponseEvent $event) {
    try {
      $is_front = \Drupal::service('path.matcher')->isFrontPage();
    }
    catch (\Exception $e) {
      $is_front = FALSE;
    }

    if ($is_front == TRUE) {
      // Check settings.php for last_state_redirect.
      $last_state_redirect = Settings::get('last_state_redirect', FALSE);
      $enable_redirect = $last_state_redirect && empty(\Drupal::request()->query->get('noredirect'));

      // Check if base site and state cookie is set and redirect.
      if (
        $enable_redirect &&
        DdBase::getEnv() != \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_LOCAL &&
        DdBase::getSiteType() == \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_BASE &&
        $_COOKIE['dd_last_state'] != ''
      ) {
        $state_domains = DdBase::getStateDomains(TRUE);
        $redirect_url = $state_domains[$_COOKIE['dd_last_state']];
        if ($redirect_url) {
          $event->setResponse(new TrustedRedirectResponse($redirect_url));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('checkForRedirection', 30);
    return $events;
  }
}
