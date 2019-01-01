<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Gift entities.
 */
class DdGiftViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Gift']['table']['base'] = array(
      'field' => 'RecordID',
      'title' => $this->t('DD Gift'),
      'help' => $this->t('The DD Gift RecordID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
