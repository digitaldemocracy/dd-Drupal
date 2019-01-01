<?php

namespace Drupal\dd_bill_alerts\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Dd bill alert history entities.
 */
class DdBillAlertHistoryViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_bill_alert_history']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Dd bill alert history'),
      'help' => $this->t('The Dd bill alert history ID.'),
    );

    return $data;
  }

}
