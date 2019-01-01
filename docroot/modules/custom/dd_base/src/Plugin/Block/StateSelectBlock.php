<?php

namespace Drupal\dd_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'StateSelectBlock' block.
 *
 * @Block(
 *  id = "state_select_block",
 *  admin_label = @Translation("State select block"),
 * )
 */
class StateSelectBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['state_select_block']['#markup'] = 'Implement StateSelectBlock.';

    return $build;
  }

}
