<?php

namespace Drupal\dd_clip\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for DD Clip Bank Quota entities.
 */
class DdClipBankQuotaViewsData extends EntityViewsData {

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
