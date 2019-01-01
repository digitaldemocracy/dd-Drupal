<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD CombinedRepresentations entities.
 */
class DdCombinedRepresentationsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['CombinedRepresentations']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD CombinedRepresentations'),
      'help' => $this->t('The DD CombinedRepresentations Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['CombinedRepresentations']['TestimonyPosition'] = array(
      'title' => t('Position'),
      'help' => t('Show Position'),
      'field' => array(
        'id' => 'testimony_position',
      ),
    );
    $data['CombinedRepresentations']['CommitteeAction'] = array(
      'title' => t('Committee Action'),
      'help' => t('Show Committee Action'),
      'field' => array(
        'id' => 'committee_action',
      ),
    );
    return $data;
  }

}
