<?php

namespace Drupal\dd_clip\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Video Tags entities.
 */
class DdVideoTagsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_video_tags']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('DD Video Tags'),
      'help' => $this->t('The DD Video Tags ID.'),
    );

    return $data;
  }

}
