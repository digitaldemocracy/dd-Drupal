<?php

namespace Drupal\dd_video\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Video entities.
 */
class DdVideoViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Video']['table']['base'] = array(
      'field' => 'vid',
      'title' => t('DD Video'),
      'help' => t('The DD Video vid'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
