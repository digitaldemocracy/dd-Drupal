<?php

namespace Drupal\dd_organization\Entity;

use Drupal\dd_base\DdBase;
use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for DD Organization entities.
 */
class DdOrganizationViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['Organizations']['table']['base'] = array(
      'field' => 'oid',
      'title' => $this->t('DD Organizations'),
      'help' => $this->t('The DD Organizations oid.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['Organizations']['oid_general_public'] = array(
      'title' => t('Link to GeneralPublic oid'),
      'help' => t('Link to GeneralPublic oid'),
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
        'base' => 'GeneralPublic',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('Organization GeneralPublic'),
      ),
    );

    $data['Organizations']['oid_lobbyist_employer'] = array(
      'title' => t('Link to LobbyistEmployer oid'),
      'help' => t('Link to LobbyistEmployer oid'),
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
        'base' => 'LobbyistEmployer',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('Organization LobbyistEmployer'),
      ),
    );

    $data['Organizations']['oid_lobbyist_representation'] = array(
      'title' => t('Link to LobbyistRepresentation oid'),
      'help' => t('Link to LobbyistRepresentation oid'),
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
        'base' => 'LobbyistRepresentation',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('Organization LobbyistRepresentation'),
      ),
    );

    $data['Organizations']['oid_alignments'] = array(
      'title' => t('Link to OrganizationAlignments oid'),
      'help' => t('Link to OrganizationAlignments oid'),
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
        'base' => 'OrgAlignments',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('Organization OrgAlignments'),
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
        ),
      ),
    );
    $data['Organizations']['oid_combined_representations'] = array(
      'title' => t('Link to CombinedRepresentations oid'),
      'help' => t('Link to CombinedRepresentations oid'),
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
        'base' => 'CombinedRepresentations',
        'base field' => 'oid',
        'relationship field' => 'oid',
        'id' => 'standard',
        'label' => t('Organization CombinedRepresentations'),
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
        ),
      ),
    );

    $data['OrganizationStateAffiliation']['table']['base'] = array(
      'field' => 'oid',
      'title' => $this->t('DD OrganizationStateAffiliation'),
      'help' => $this->t('The OrganizationStateAffiliation oid.'),
      'database' => \Drupal\dd_base\DdBase::getDddbName(),
    );

    $data['OrganizationStateAffiliation']['table']['group'] = t('OrganizationStateAffiliation');
    $data['OrganizationStateAffiliation']['oid'] = array(
      'title' => t('OrganizationStateAffiliation OID'),
      'help' => t('OrganizationStateAffiliation OID'),
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

    $data['OrganizationStateAffiliation']['state'] = array(
      'title' => t('OrganizationStateAffiliation State'),
      'help' => t('OrganizationStateAffiliation State'),
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

    $data['OrganizationStateAffiliation']['table']['join'] = array(
      'Organizations' => array(
        'left_field' => 'oid',
        'field' => 'oid',
        'extra' => array(
          0 => array(
            'field' => 'state',
            'value' => DdBase::getCurrentState(),
          ),
        ),
        'type' => 'LEFT',
      ),
    );
    return $data;
  }

}
