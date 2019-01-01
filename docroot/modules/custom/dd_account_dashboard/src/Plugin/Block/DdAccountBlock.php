<?php

namespace Drupal\dd_account_dashboard\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a user account menu block
 *
 * @Block(
 *   id = "dd_account_block",
 *   admin_label = @Translation("DD Account Block"),
 *  )
 */
class DdAccountBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $show_dashboard = FALSE;
    $user_roles = \Drupal::currentUser()->getRoles();

    return array(
      '#theme' => 'dd_account_dashboard',
      '#userid' => \Drupal::currentUser()->id(),
      '#cache' => ['max-age' => 0],
    );
  }

}
