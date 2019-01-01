<?php

namespace Drupal\dd_account_dashboard\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_base\DdBase;

/**
 * A Dd Alert Content controller.
 */
class DdAlertContentController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content() {
    $active_states = \Drupal\dd_base\DdBase::getActiveStates();
    $state_urls = \Drupal\dd_base\DdBase::getStateDomains(TRUE, TRUE, TRUE);
    $current_state = \Drupal\dd_base\DdBase::getCurrentState();
    $options = [];
    foreach ($active_states as $active_state) {
      $options[$active_state . '~' . $state_urls[$active_state]] = $active_state;
    }

    // Attach state select dropdown for non-whitelabel sites.
    if (DdBase::getSiteType() != \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      $build['alert_state'] = [
        '#name' => 'alert-state',
        '#type' => 'select',
        '#title' => 'Show Alerts For State:',
        '#options' => $options,
        '#value' => $current_state . '~' . $state_urls[$current_state],
        '#weight' => -1,
        '#attributes' => ['id' => 'edit-alert-state'],
      ];
    }

    $build['#attached']['library'][] = 'dd/dd-alerts';

    return $build;
  }

}
