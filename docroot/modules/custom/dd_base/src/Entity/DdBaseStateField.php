<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dd_base\DdBase;

/**
 * Class DdBaseStateField
 * 
 * Includes state fields for other classes to inherit.
 * @package Drupal\dd_base\Entity
 */
class DdBaseStateField extends DdBaseContentEntity implements DdBaseStateFieldInterface {
  /**
   * Return current state for codebase.
   *
   * @return string
   *   Current State.
   */
  public static function getCurrentState() {
    return DdBase::getCurrentState();
  }

  /**
   * Return current site type for codebase.
   *
   * @return string
   *   Current Site Type.
   */
  public static function getSiteType() {
    return DdBase::getSiteType();
  }

  /**
   * @inheritDoc
   */
  public function getState() {
    return $this->get('state')->value;
  }

  /**
   * @inheritDoc
   */
  public function setState($state) {
    $this->set('state', $state);
    return $this;
  }

  /**
   * Determines if entity type uses a state field.
   *
   * @param string $entity_type
   *   Entity type to check
   *
   * @returns bool
   *   TRUE if entity uses field, FALSE otherwise.
   */
  public static function doesEntityUseStateField($entity_type) {
    return in_array($entity_type, self::getStateFieldEntities());
  }

  /**
   * Get entities that extend DdBaseStateField.
   * @return array
   *   Array of entities names.
   */
  public static function getStateFieldEntities() {
    $state_field_entities = array();
    $state_classes = array(
      'Drupal\dd_base\Entity\DdBaseStateField',
    );
    $entity_manager = \Drupal::entityTypeManager();
    $bundle_info = \Drupal::service("entity_type.bundle.info")->getAllBundleInfo();

    foreach ($bundle_info as $bundle => $info) {
      $definition = $entity_manager->getDefinition($bundle);
      $class = $definition->getClass();
      $parent = get_parent_class($class);
      $parent_parent = get_parent_class($parent);
      if (in_array($parent, $state_classes) || in_array($parent_parent, $state_classes)) {
        //@todo Person is a special case, it extends DdBaseStateField but only for classes extending DdPerson's sake.
        if ($bundle != 'dd_person' && $bundle != 'dd_authors') {
          $state_field_entities[] = $bundle;
        }
      }
    }
    return $state_field_entities;
  }

  /**
   * Determines if table uses a state field.
   *
   * @param string $table_name
   *   Table name to check
   *
   * @returns bool
   *   TRUE if table uses field, FALSE otherwise.
   */
  public static function doesTableUseStateField($table_name) {
    $state_tables = array(
      'Behests',
      'Bill',
      'BillAnalysis',
      'BillTypes',
      'BillVersion',
      'BillVersionCurrent',
      'BillVoteDetail',
      'CombinedLobbyistEmployers',
      'CombinedRepresentations',
      'Committee',
      'CommitteeAuthors',
      'ConsultantServesOn',
      'Contribution',
      'currentUtterance',
      'currentUtteranceUnsorted',
      'District',
      'GeneralPublic',
      'Gift',
      'GiftCombined',
      'Hearing',
      'House',
      'KnownClients',
      'LegAnalystOffice',
      'LegAnalystOfficeRepresentation',
      'LegOfficePersonnel',
      'LegislativeStaff',
      'LegislativeStaffRepresentation',
      'Legislator',
      'LegislatureOffice',
      'LobbyingContracts',
      'LobbyingFirmState',
      'Lobbyist',
      'LobbyistDirectEmployment',
      'LobbyistEmployer',
      'LobbyistEmployment',
      'LobbyistRepresentation',
      'OfficePersonnel',
      'Payors',
      'PersonClassifications',
      'servesOn',
      'Session',
      'SpeakerParticipation',
      'SpeakerProfileTypes',
      'StateAgency',
      'StateAgencyRep',
      'StateAgencyRepRepresentation',
      'StateConstOffice',
      'StateConstOfficeRep',
      'StateConstOfficeRepRepresentation',
      'Term',
      'TT_EditorStates',
      'TT_Videos',
      'Utterance',
      'Video',

    );

    return in_array($table_name, $state_tables);
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    if (self::doesEntityUseStateField($entity_type->id())) {
      $fields['state'] = BaseFieldDefinition::create('string')
        ->setLabel(t('State'))
        ->setDescription(t('The state of the entity.'))
        ->setSettings(array(
          'max_length' => 2,
          'text_processing' => 0,
        ))
        ->setDefaultValue('')
        ->setDisplayOptions('view', array(
          'label' => 'inline',
          'type' => 'string',
          'weight' => 50,
        ))
        ->setDisplayOptions('form', array(
          'type' => 'string_textfield',
          'weight' => 50,
        ))
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE);
    }
    return $fields;
  }
}
