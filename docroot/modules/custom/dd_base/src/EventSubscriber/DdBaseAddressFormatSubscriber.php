<?php

namespace Drupal\dd_base\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\address\Event\AddressEvents;

class DdBaseAddressFormatSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[AddressEvents::ADDRESS_FORMAT][] = array('onGetDefinition', 0);
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  public function onGetDefinition($event) {
    $definition = $event->getDefinition();
    // This makes city (locality) field required and leaves
    // the rest address fields as optional.
    $definition['required_fields'] = [];
    $event->setDefinition($definition);
  }

}
