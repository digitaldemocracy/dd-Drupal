<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD BillVoteDetail entities.
 */
class DdBillVoteDetailViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['BillVoteDetail']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD BillVoteDetail'),
      'help' => $this->t('The DD BillVoteDetail Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }
}
