<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD LobbyingFirmState entity.
 *
 * @ingroup dd_lobbying
 *
 * @ContentEntityType(
 *   id = "dd_lobbying_firmstate",
 *   label = @Translation("DD LobbyingFirmState"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbying\DdLobbyingAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyingFirmStateViewsData"
 *   },
 *   base_table = "LobbyingFirmState",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "rpt_date_ts"
 *   },
 * )
 */
class DdLobbyingFirmState extends DdBaseStateField implements DdLobbyingFirmStateInterface {

  /**
   * {@inheritdoc}
   */
  public function getFilerid() {
    return $this->get('filer_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFilerid($filer_id) {
    $this->set('filer_id', $filer_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRptDate() {
    return $this->get('rpt_date_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRptDate($rpt_date_ts) {
    $this->set('rpt_date_ts', $rpt_date_ts);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIsBegYr() {
    return $this->get('Is_beg_yr')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsBegYr($is_beg_yr) {
    $this->set('Is_beg_yr', $is_beg_yr);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIsEndYr() {
    return $this->get('Is_end_yr')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsEndYr($is_end_yr) {
    $this->set('Is_end_yr', $is_end_yr);
    return $this;
  }

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

    $fields['filer_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Filer ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rpt_date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Report Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['Is_beg_yr'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Beginning Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['Is_end_yr'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('End Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['filer_naml'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Filer Naml'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
