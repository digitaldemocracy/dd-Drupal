<?php

namespace Drupal\dd_clip\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Clip entities.
 */
class DdClipViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_clip']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('DD Clip'),
      'help' => $this->t('The DD Clip ID.'),
    );

    return $data;
  }

}
