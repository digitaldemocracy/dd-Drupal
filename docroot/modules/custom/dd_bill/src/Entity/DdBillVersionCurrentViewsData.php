<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Bill Version Current entities.
 */
class DdBillVersionCurrentViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['BillVersionCurrent']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD BillVersionCurrent'),
      'help' => t('The DD BillVersionCurrent Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['BillVersionCurrent']['authors'] = array(
      'title' => t('BillVersionCurrent Authors'),
      'help' => t('BillVersionCurrent Authors'),
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
        'base' => 'authors',
        'base field' => 'vid',
        'relationship field' => 'vid',
        'id' => 'standard',
        'label' => 'BillVersionCurrent Authors',
      ),
    );

    $data['BillVersionCurrent']['bid_bill'] = array(
      'title' => t('BillVersionCurrent Bill'),
      'help' => t('BillVersionCurrent Bill'),
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
        'base' => 'Bill',
        'base field' => 'bid',
        'relationship field' => 'bid',
        'id' => 'standard',
        'label' => 'BillVersionCurrent Bill',
      ),
    );
    return $data;
  }

}
