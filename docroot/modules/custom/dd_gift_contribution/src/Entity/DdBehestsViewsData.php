<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Behests entities.
 */
class DdBehestsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['Behests']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD Behests'),
      'help' => $this->t('The DD Behests Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
