<?php

namespace Drupal\dd_fax_service\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Dd fax service history entities.
 */
class DdFaxServiceHistoryViewsData extends EntityViewsData {

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
