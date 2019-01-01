<?php

namespace Drupal\dd_base\TwigExtension;

class Unescape extends \Twig_Extension {
  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('unescape', array($this, 'unescape')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function unescape($value) {
    return html_entity_decode($value);
  }
}
