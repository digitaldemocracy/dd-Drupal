<?php

namespace Drupal\dd_account_dashboard\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Saved Content entities.
 */
class DdSavedContentViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_saved_content']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('DD Saved Content'),
      'help' => $this->t('The DD Saved Content ID.'),
    );

    return $data;
  }

}
