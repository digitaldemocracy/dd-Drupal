<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Bill entities.
 */
class DdBillViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Bill']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD Bill'),
      'help' => t('The DD Bill Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Bill']['bid_billversioncurrent'] = array(
      'title' => t('Bill BillVersionCurrent bid'),
      'help' => t('Bill BillVersionCurrent bid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'string',
      ),
      'argument' => array(
        'id' => 'string',
      ),
      'relationship' => array(
        'base' => 'BillVersionCurrent',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => 'BillVersionCurrent Bill',
      ),
    );

    $data['Bill']['bid_author'] = array(
      'title' => t('Bill Author'),
      'help' => t('Bill Author'),
      'field' => array(
        'id' => 'dd_bill_authors',
      ),
    );
    return $data;
  }

}
