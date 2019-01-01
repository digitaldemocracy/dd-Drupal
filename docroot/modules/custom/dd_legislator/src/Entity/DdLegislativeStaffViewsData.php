<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Legislative Staff entities.
 */
class DdLegislativeStaffViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['LegislativeStaff']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD LegislativeStaff'),
      'help' => $this->t('The LegislativeStaff PID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
