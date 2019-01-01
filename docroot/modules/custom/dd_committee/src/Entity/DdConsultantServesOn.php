<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD ConsultantServesOn entity.
 *
 * @ingroup dd_committee
 *
 * @ContentEntityType(
 *   id = "dd_consultant_serves_on",
 *   label = @Translation("DD ConsultantServesOn"),
 *   handlers = {
 *     "storage" = "Drupal\dd_committee\Entity\Sql\DdConsultantServesOnSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_committee\Entity\DdConsultantServesOnViewsData",
 *
 *     "access" = "Drupal\dd_committee\DdConsultantServesOnAccessControlHandler",
 *   },
 *   base_table = "ConsultantServesOn",
 *   admin_permission = "administer dd committee entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "position",
 *   },
 * )
 */
class DdConsultantServesOn extends DdPersonContentEntityBase implements DdConsultantServesOnInterface {
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
  public function getSessionYear() {
    return $this->get('session_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSessionYear($session_year) {
    $this->set('session_year', $session_year);
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
   * {@inheritdoc}
   */
  public function getPosition() {
    return $this->get('position')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPosition($position) {
    $this->set('position', $position);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentFlag() {
    return $this->get('current_flag')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentFlag($current_flag) {
    $this->set('current_flag', $current_flag);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStartDate() {
    // @todo Change to start_date_ts when available.
    return $this->get('start_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStartDate($start_date) {
    // @todo Change to start_date_ts when available.
    $this->set('start_date', $start_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndDate() {
    // @todo Change to end_date_ts when available.
    return $this->get('end_date')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEndDate($end_date) {
    // @todo Change to end_date_ts when available.
    $this->set('end_date', $end_date);
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

    $fields['session_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee ID'))
      ->setSetting('target_type', 'dd_committee')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['position'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Position'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['current_flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Current Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // @todo Change to start_date_ts when available.
    $fields['start_date'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Start Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // @todo Change to end_date_ts when available.
    $fields['end_date'] = BaseFieldDefinition::create('string')
      ->setLabel(t('End Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
