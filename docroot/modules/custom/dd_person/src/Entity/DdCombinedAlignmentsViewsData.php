<?php

namespace Drupal\dd_person\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD CombinedAlignment entities.
 */
class DdCombinedAlignmentsViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['CombinedAlignmentScores']['table']['base'] = array(
      'field' => 'dr_id',
      'title' => $this->t('DD CombinedAlignmentScores'),
      'help' => $this->t('The CombinedAlignmentScores Drupal ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );
    $data['CombinedAlignmentScores']['orgconcept_oid'] = array(
      'title' => t('CombinedAlignmentScores OrgConcept oid'),
      'help' => t('CombinedAlignmentScores OrgConcept oid'),
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
        'base' => 'OrgConcept',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => 'CombinedAlignmentScores OrgConcept oid',
      ),
    );

    $data['CombinedAlignmentScores']['leg_info'] = array(
      'title' => t('Legislator Info'),
      'help' => t('Legislator Info, such as house/party/district'),
      'field' => array(
        'id' => 'dd_legislator_info',
      ),
    );

    $data['OrgConcept']['table']['group'] = t('OrgConcept');
    $data['OrgConcept']['table']['base'] = array(
      'field' => 'oid',
      'title' => $this->t('DD OrgConcept oid'),
      'help' => $this->t('The OrgConcept Organization ID.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['OrgConcept']['oid'] = array(
      'title' => t('OrgConcept Oid'),
      'help' => t('OrgConcept Oid'),
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

    $data['OrgConcept']['name'] = array(
      'title' => t('OrgConcept Name'),
      'help' => t('OrgConcept Name'),
      'field' => array(
        'id' => 'standard',
      ),
      'sort' => array(
        'id' => 'standard',
      ),
      'filter' => array(
        'id' => 'string',
      ),
      'argument' => array(
        'id' => 'string',
      ),
    );
    $data['OrgConcept']['meter_flag'] = array(
      'title' => t('OrgConcept Meter Flag'),
      'help' => t('OrgConcept Meter Flag'),
      'field' => array(
        'id' => 'boolean',
      ),
      'sort' => array(
        'id' => 'boolean',
      ),
      'filter' => array(
        'id' => 'boolean',
      ),
      'argument' => array(
        'id' => 'boolean',
      ),
    );

    return $data;
  }

}
