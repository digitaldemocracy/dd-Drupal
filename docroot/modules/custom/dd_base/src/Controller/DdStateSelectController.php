<?php

namespace Drupal\dd_base\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DdStateSelectController.
 *
 * @package Drupal\dd_base\Controller
 */
class DdStateSelectController extends ControllerBase {

  /**
   * Showstateselectblock.
   *
   * @return string
   *   Return Hello string.
   */
  public function ShowStateSelectBlock() {
    $block = \Drupal\block\Entity\Block::load('stateselectblock');
    $stateblock = \Drupal::entityTypeManager()
      ->getViewBuilder('block')
      ->view($block);

    // Unset #cache because of lazy_builder.
    unset($stateblock['#cache']);
    return $stateblock;
  }

}
