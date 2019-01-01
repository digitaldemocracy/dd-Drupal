<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD ServesOn entity.
 *
 * @ingroup dd_committee
 *
 * @ContentEntityType(
 *   id = "dd_serves_on",
 *   label = @Translation("DD ServesOn"),
 *   handlers = {
 *     "storage" = "Drupal\dd_committee\Entity\Sql\DdCommitteeServesOnSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_committee\DdServesOnListBuilder",
 *     "views_data" = "Drupal\dd_committee\Entity\DdServesOnViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_committee\Form\DdServesOnForm",
 *       "edit" = "Drupal\dd_committee\Form\DdServesOnForm",
 *     },
 *     "access" = "Drupal\dd_committee\DdServesOnAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_committee\DdServesOnHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "servesOn",
 *   admin_permission = "administer dd serveson entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "cid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_serves_on/{dd_serves_on}",
 *     "edit-form" = "/admin/structure/dd_serves_on/{dd_serves_on}/edit",
 *     "collection" = "/admin/structure/dd_serves_on",
 *   },
 *   field_ui_base_route = "dd_serves_on.settings"
 * )
 */
class DdServesOn extends DdPersonContentEntityBase implements DdServesOnInterface {
  /**
   * Get Serves On Drupal IDs For a Person ID.
   *
   * @param int $pid
   *   Person ID.
   *
   * @param bool $current_only
   *   If TRUE, returns only current committees.
   *
   * @return array
   *   Array of servesOn Drupal IDs.
   */
  public static function getServesOnIdsForPid($pid, $current_only = FALSE) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('servesOn', 'so');
    $query->fields('so', ['dr_id']);
    $query->condition('so.pid', $pid);
    if ($current_only) {
      $query->condition('current_flag', 1);
    }
    return $query->execute()->fetchCol();
  }

  /**
   * Get Serves On Drupal IDs For a Committee ID.
   *
   * @param int $cid
   *   Committee ID.
   *
   * @return array
   *   Array of servesOn Drupal IDs.
   */
  public static function getServesOnIdsForCid($cid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('servesOn', 'so');
    $query->fields('so', ['dr_id']);
    $query->condition('so.cid', $cid);
    return $query->execute()->fetchCol();
  }

  /**
   * @inheritDoc
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCid() {
    return $this->get('cid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCid($cid) {
    $this->set('cid', $cid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getYear() {
    return $this->get('year')->value;
  }

  /**
   * @inheritDoc
   */
  public function setYear($year) {
    $this->set('year', $year);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getHouse() {
    return $this->get('house')->value;
  }

  /**
   * @inheritDoc
   */
  public function setHouse($house) {
    $this->set('house', $house);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getOid() {
    return $this->get('oid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setOid($oid) {
    $this->set('oid', $oid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPosition() {
    return $this->get('position')->value;
  }

  /**
   * @inheritDoc
   */
  public function setPosition($position) {
    $this->set('position', $position);
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
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'number_integer',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setSettings(array(
        'max_length' => 200,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 20,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['position'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Position'))
      ->setSettings(array(
        'max_length' => 200,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 30,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['cid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee ID'))
      ->setSetting('target_type', 'dd_committee')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['current_flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Current Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
