<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for DD Campaign Visitor entities.
 */
class DdCampaignVisitorViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dd_campaign_visitor']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('DD Campaign Visitor'),
      'help' => $this->t('The DD Campaign Visitor ID.'),
    );

    return $data;
  }

}
