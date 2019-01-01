<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD LobbyingFirm entity.
 *
 * @ingroup dd_lobbying
 *
 * @ContentEntityType(
 *   id = "dd_lobbying_firm",
 *   label = @Translation("DD LobbyingFirm"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbying\DdLobbyingAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyingFirmViewsData"
 *   },
 *   base_table = "LobbyingFirm",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "filer_naml"
 *   },
 * )
 */
class DdLobbyingFirm extends DdBaseContentEntity implements DdLobbyingFirmInterface {

  /**
   * {@inheritdoc}
   */
  public function getFilerNaml() {
    return $this->get('filer_naml')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFilerNaml($filer_naml) {
    $this->set('filer_naml', $filer_naml);
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

    $fields['filer_naml'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Filer Naml'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
