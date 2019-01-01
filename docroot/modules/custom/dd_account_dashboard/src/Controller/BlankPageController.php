<?php

namespace Drupal\dd_account_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * An empty page controller.
 */
class BlankPageController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $build = array(
      '#type' => 'markup',
      '#markup' => '',
    );
    return $build;
  }

}
