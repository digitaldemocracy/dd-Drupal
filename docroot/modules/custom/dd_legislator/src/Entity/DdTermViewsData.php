<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Term entities.
 */
class DdTermViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Term']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD Term'),
      'help' => $this->t('The DD Term Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
