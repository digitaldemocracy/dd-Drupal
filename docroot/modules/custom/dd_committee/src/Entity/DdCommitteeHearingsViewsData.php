<?php

namespace Drupal\dd_committee\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Committee entities.
 */
class DdCommitteeHearingsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['CommitteeHearings']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD CommitteeHearings'),
      'help' => t('The DD CommitteeHearings Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
