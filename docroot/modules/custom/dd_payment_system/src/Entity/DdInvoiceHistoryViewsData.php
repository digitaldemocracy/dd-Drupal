<?php

namespace Drupal\dd_payment_system\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Dd invoice history entities.
 */
class DdInvoiceHistoryViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
