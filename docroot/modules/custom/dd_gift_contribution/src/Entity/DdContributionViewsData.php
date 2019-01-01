<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Contribution entities.
 */
class DdContributionViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Contribution']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD Contribution'),
      'help' => $this->t('The DD Contribution Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
