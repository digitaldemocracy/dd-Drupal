<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD LobbyistRepresentation entity.
 *
 * @ingroup dd_lobbyist
 *
 * @ContentEntityType(
 *   id = "dd_lobbyist_representation",
 *   label = @Translation("DD LobbyistRepresentation"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbyist\DdLobbyistAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyistRepresentationViewsData"
 *   },
 *   base_table = "LobbyistRepresentation",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "hearing_date_ts"
 *   },
 * )
 */
class DdLobbyistRepresentation extends DdBaseStateField implements DdLobbyistRepresentationInterface {

  /**
   * {@inheritdoc}
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOid() {
    return $this->get('oid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setOid($oid) {
    $this->set('oid', $oid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHearingDate() {
    return $this->get('hearing_date_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHearingDate($hearing_date) {
    $this->set('hearing_date_ts', $hearing_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHid() {
    return $this->get('hid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setHid($hid) {
    $this->set('hid', $hid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDid() {
    return $this->get('did')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setDid($did) {
    $this->set('did', $did);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['oid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization ID'))
      ->setSettings(array(
        'target_type' => 'dd_organization',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hearing_date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Hearing Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSettings(array(
        'target_type' => 'dd_hearing',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Discussion ID'))
      ->setSettings(array(
        'target_type' => 'dd_bill_discussion',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
