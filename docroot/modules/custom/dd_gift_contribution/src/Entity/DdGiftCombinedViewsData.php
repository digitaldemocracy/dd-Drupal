<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD GiftCombined entities.
 */
class DdGiftCombinedViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['GiftCombined']['table']['base'] = array(
      'field' => 'RecordID',
      'title' => $this->t('DD GiftCombined'),
      'help' => $this->t('The DD GiftCombined RecordID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
