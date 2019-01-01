<?php

namespace Drupal\dd_person\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Authors entities.
 */
class DdAuthorsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['authors']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD Authors'),
      'help' => t('The DD Authors Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['authors']['vid_billversioncurrent'] = array(
      'title' => t('Bill BillVersionCurrent vid'),
      'help' => t('Bill BillVersionCurrent vid'),
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
        'base field' => 'vid',
        'relationship field' => 'vid',
        'id' => 'standard',
        'label' => 'BillVersionCurrent Authors',
      ),
    );
    return $data;
  }

}
