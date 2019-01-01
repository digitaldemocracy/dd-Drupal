<?php

namespace Drupal\dd_committee\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Committee Authors entities.
 */
class DdCommitteeAuthorsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['CommitteeAuthors']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => t('DD CommitteeAuthors'),
      'help' => t('The DD CommitteeAuthors Drupal ID'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    return $data;
  }

}
