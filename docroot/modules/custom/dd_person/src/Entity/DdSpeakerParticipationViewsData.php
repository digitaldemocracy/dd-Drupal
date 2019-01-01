<?php

namespace Drupal\dd_person\Entity;

use Drupal\dd_base\DdBase;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD SpeakerParticipation entities.
 */
class DdSpeakerParticipationViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['SpeakerParticipation']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD SpeakerParticipation'),
      'help' => $this->t('The DD SpeakerParticipation dr_id.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['SpeakerParticipation']['person_classifications'] = array(
      'title' => t('Link to Person Classifications'),
      'help' => t('Link to Person Classifications'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'numeric',
      ),
      'argument' => array(
        'id' => 'numeric',
      ),
      'relationship' => array(
        'base' => 'PersonClassifications',
        'base field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'PersonClassifications Person',
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
          1 => array(
            'field' => 'session_year',
            'left_field' => 'session_year',
          ),
        ),
      ),
    );

    $data['SpeakerParticipation']['session_year']['filter'] = array('id' => 'dd_year');

    return $data;
  }

}
