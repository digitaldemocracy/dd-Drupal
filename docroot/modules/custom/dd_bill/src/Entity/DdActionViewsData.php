<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Action entities.
 */
class DdActionViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Action']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD Action'),
      'help' => $this->t('The DD Action Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }
}
