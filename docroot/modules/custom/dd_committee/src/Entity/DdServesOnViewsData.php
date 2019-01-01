<?php

namespace Drupal\dd_committee\Entity;

use Drupal\dd_base\DdBase;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD ServesOn entities.
 */
class DdServesOnViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['servesOn']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD ServesOn'),
      'help' => t('The DD ServesOn Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );
    $data['servesOn']['serveson_term'] = array(
      'title' => t('ServesOn Term pid'),
      'help' => t('ServesOn Term  pid'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'Term',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => t('ServesOn Term'),
      ),
    );
    return $data;
  }

}
