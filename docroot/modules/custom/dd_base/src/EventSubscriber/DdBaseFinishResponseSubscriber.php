<?php

namespace Drupal\dd_base\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DdBaseFinishResponseSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('onRespond');
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  public function onRespond(FilterResponseEvent $event) {
    $response = $event->getResponse();
    $route_match = \Drupal::routeMatch();
    $route_name = $route_match->getRouteName();

    // Remove X-Frame-Options to allow iframe embed for widget.
    if ($route_name == 'entity.node.canonical') {
      $node = \Drupal::routeMatch()->getParameter('node');
      if ($node->getType() == 'widget') {
        $response->headers->remove('X-Frame-Options');
      }
    }
    elseif ($route_name === 'dd_clip.clip_view') {
      $response->headers->remove('X-Frame-Options');
    } 
    elseif ($route_name === 'view.alignments.page_2') {
      $response->headers->remove('X-Frame-Options');
    } 
  }

}
