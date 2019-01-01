<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD CombinedRepresentations entity.
 *
 * @ingroup dd_lobbyist
 *
 * @ContentEntityType(
 *   id = "dd_combined_representations",
 *   label = @Translation("DD CombinedRepresentations"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbyist\DdLobbyistAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdCombinedRepresentationsViewsData"
 *   },
 *   base_table = "CombinedRepresentations",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "pid"
 *   },
 * )
 */
class DdCombinedRepresentations extends DdBaseStateField implements DdCombinedRepresentationsInterface {

  /**
   * Get Person.
   *
   * @param bool $get_entity
   *   If TRUE, returns entity reference object otherwise ID
   *
   * @return mixed
   *   Entity reference object or ID.
   */
  public function getPid($get_entity = FALSE) {
    $entity = $this->get('pid')->first()->get('entity');
    if ($get_entity) {
      return $entity->getTarget()->getValue();
    }

    return $entity->getValue()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * Get Hearing.
   *
   * @param bool $get_entity
   *   If TRUE, returns entity reference object otherwise ID
   *
   * @return mixed
   *   Entity reference object or ID.
   */
  public function getHid($get_entity = FALSE) {
    $entity = $this->get('hid')->first()->get('entity');
    if ($get_entity) {
      return $entity->getTarget()->getValue();
    }

    return $entity->getValue()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setHid($hid) {
    $this->set('hid', $hid);
    return $this;
  }

  /**
   * Get BillDiscussion.
   *
   * @param bool $get_entity
   *   If TRUE, returns entity reference object otherwise ID
   *
   * @return mixed
   *   Entity reference object or ID.
   */
  public function getDid($get_entity = FALSE) {
    $entity = $this->get('did')->first()->get('entity');
    if ($get_entity) {
      return $entity->getTarget()->getValue();
    }

    return $entity->getValue()->id();
  }

  /**
   * {@inheritdoc}
   */
  public function setDid($did) {
    $this->set('did', $did);
    return $this;
  }

  /**
   * Get Organization.
   *
   * @param bool $get_entity
   *   If TRUE, returns entity reference object otherwise ID
   *
   * @return mixed
   *   Entity reference object or ID.
   */
  public function getOid($get_entity = FALSE) {
    $entity = $this->get('oid')->first()->get('entity');
    if ($get_entity) {
      return $entity->getTarget()->getValue();
    }

    return $entity->getValue()->id();
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
  public function getYear() {
    return $this->get('year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setYear($year) {
    $this->set('year', $year);
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
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSettings(array(
        'target_type' => 'dd_hearing',
      ))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('BillDiscussion ID'))
      ->setSettings(array(
        'target_type' => 'dd_bill_discussion',
      ))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['oid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization ID'))
      ->setSettings(array(
        'target_type' => 'dd_organization',
      ))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
