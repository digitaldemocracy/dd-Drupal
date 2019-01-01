<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;
use Drupal\dd_base\DdBase;

/**
 * Provides Views data for DD LegParticipation entities.
 */
class DdLegParticipationViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['LegParticipation']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD LegParticipation'),
      'help' => $this->t('The DD LegParticipation PID.'),
      'database' => DdBase::getDddbName(),
    );

    $data['LegAvgPercentParticipation'] = array();
    $data['LegAvgPercentParticipation']['table']['provider'] = 'dd_legislator';
    $data['LegAvgPercentParticipation']['table']['group'] = t('LegAvgPercentParticipation Table');
    $data['LegAvgPercentParticipation']['table']['base'] = array(
      'field' => 'pid',
      'title' => $this->t('DD LegAvgPercentParticipation'),
      'help' => $this->t('The DD LegAvgPercentParticipation PID.'),
      'database' => DdBase::getDddbName(),
    );
    $data['LegAvgPercentParticipation']['table']['join'] = array(
      'LegParticipation' => array(
        'left_field' => 'pid',
        'field' => 'pid',
      ),
    );
    $data['LegAvgPercentParticipation']['pid'] = array(
      'title' => t('pid'),
      'help' => t('pid'),
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
    );
    $data['LegAvgPercentParticipation']['person_pid'] = array(
      'title' => t('Person pid'),
      'help' => t('Person pid'),
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
        'base' => 'Person',
        'base field' => 'pid',
        'field' => 'pid',
        'relationship field' => 'pid',
        'id' => 'standard',
        'label' => 'LegAvgPercentParticipation Person',
      ),
    );

    $data['LegAvgPercentParticipation']['AvgPercentParticipation'] = array(
      'title' => t('AvgPercentParticipation'),
      'help' => t('AvgPercentParticipation'),
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
    );
    return $data;
  }

}
