<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Motion entities.
 */
class DdMotionViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Motion']['table']['base'] = array(
      'field' => 'mid',
      'title' => $this->t('DD Motion'),
      'help' => $this->t('The DD Motion mid.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }
}
