<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Bill Version entities.
 */
class DdBillVersionViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['BillVersion']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD BillVersion'),
      'help' => t('The DD BillVersion Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
