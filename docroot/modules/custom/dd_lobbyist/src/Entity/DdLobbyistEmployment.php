<?php

namespace Drupal\dd_lobbyist\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD LobbyistEmployment entity.
 *
 * @ingroup dd_lobbyist
 *
 * @ContentEntityType(
 *   id = "dd_lobbyist_employment",
 *   label = @Translation("DD LobbyistEmployment"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "access" = "Drupal\dd_lobbyist\DdLobbyistAccessControlHandler",
 *     "views_data" = "Drupal\dd_lobbyist\Entity\DdLobbyistEmploymentViewsData"
 *   },
 *   base_table = "LobbyistEmployment",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "rpt_date_ts"
 *   },
 * )
 */
class DdLobbyistEmployment extends DdBaseStateField implements DdLobbyistEmploymentInterface {

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
  public function getSenderId() {
    return $this->get('sender_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSenderId($sender_id) {
    $this->set('sender_id', $sender_id);
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

    $fields['sender_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Sender ID'))
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

    return $fields;
  }

}
