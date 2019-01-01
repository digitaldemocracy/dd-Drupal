<?php

namespace Drupal\dd_person\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD General Public entities.
 */
class DdGeneralPublicViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['GeneralPublic']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD GeneralPublic'),
      'help' => $this->t('The DD GeneralPublic PID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
