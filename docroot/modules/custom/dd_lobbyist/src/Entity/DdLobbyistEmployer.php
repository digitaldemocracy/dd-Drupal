<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD LobbyistEmployer entity.
 *
 * @ingroup dd_lobbyist
 *
 * @ContentEntityType(
 *   id = "dd_lobbyist_employer",
 *   label = @Translation("DD LobbyistEmployer"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbyist\DdLobbyistAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyistEmployerViewsData"
 *   },
 *   base_table = "LobbyistEmployer",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "filer_id"
 *   },
 * )
 */
class DdLobbyistEmployer extends DdBaseContentEntity implements DdLobbyistEmployerInterface {

  /**
   * {@inheritdoc}
   */
  public function getFilerId() {
    return $this->get('filer_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFilerId($filer_id) {
    $this->set('filer_id', $filer_id);
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
  public function getCoalition() {
    return $this->get('coalition')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCoalition($coalition) {
    $this->set('coalition', $coalition);
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

    $fields['filer_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Filer ID'))
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

    $fields['coalition'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Is Coalition'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
