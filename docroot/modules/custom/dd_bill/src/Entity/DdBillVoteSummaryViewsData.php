<?php

namespace Drupal\dd_bill\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD BillVoteSummary entities.
 */
class DdBillVoteSummaryViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['BillVoteSummary']['table']['base'] = array(
      'field' => 'voteId',
      'title' => $this->t('DD BillVoteSummary'),
      'help' => $this->t('The DD BillVoteSummary voteId.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }
}
