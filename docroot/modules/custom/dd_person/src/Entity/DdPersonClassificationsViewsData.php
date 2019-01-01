<?php

namespace Drupal\dd_person\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Person Classifications entities.
 */
class DdPersonClassificationsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['PersonClassifications']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD PersonClassifications'),
      'help' => $this->t('The DD PersonClassifications Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );
    $data['PersonClassifications']['person_affiliations_field'] = array(
      'title' => t('Person Affiliations Custom Field'),
      'help' => t('Person Affiliations Custom Field'),
      'field' => array(
        'id' => 'dd_person_affiliations',
      ),
    );
    $data['PersonClassifications']['person_classifications_field'] = array(
      'title' => t('Person Classifications Custom Field'),
      'help' => t('Person Classifications Custom Field'),
      'field' => array(
        'id' => 'dd_person_classifications',
      ),
    );
    $data['PersonClassifications']['dd_lobbyist_known_clients'] = array(
      'title' => t('Lobbyist Known Clients Custom Field'),
      'help' => t('Lobbyist Known Clients Custom Field'),
      'field' => array(
        'id' => 'dd_lobbyist_known_clients',
      ),
    );

    $data['PersonClassifications']['dd_person_known_employers'] = array(
      'title' => t('Person Known Employers Custom Field'),
      'help' => t('Person Known Employers Custom Field'),
      'field' => array(
        'id' => 'dd_person_known_employers',
      ),
    );
    return $data;
  }

}
