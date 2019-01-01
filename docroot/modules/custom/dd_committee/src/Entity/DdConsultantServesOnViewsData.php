<?php

namespace Drupal\dd_committee\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD ConsultantServesOn entities.
 */
class DdConsultantServesOnViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['ConsultantServesOn']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD ConsultantServesOn'),
      'help' => t('The DD ConsultantServesOn Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
