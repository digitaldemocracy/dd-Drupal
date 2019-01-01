<?php

namespace Drupal\dd_fax_service_payment\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Dd fax service payment entity entities.
 */
class DdFaxServicePaymentEntityViewsData extends EntityViewsData {

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
