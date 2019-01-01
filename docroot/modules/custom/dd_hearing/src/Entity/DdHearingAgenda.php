<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD HearingAgenda entity.
 *
 * @ingroup dd_hearing
 *
 * @ContentEntityType(
 *   id = "dd_hearing_agenda",
 *   label = @Translation("DD HearingAgenda"),
 *   handlers = {
 *     "storage" = "Drupal\dd_hearing\Entity\Sql\DdHearingAgendaSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_hearing\Entity\DdHearingAgendaViewsData",
 *   },
 *   base_table = "HearingAgenda",
 *   admin_permission = "administer dd hearing entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "bid",
 *   },
 * )
 */
class DdHearingAgenda extends DdBaseContentEntity implements DdHearingAgendaInterface {
  /**
   * {@inheritdoc}
   */
  public function getDateCreated() {
    return $this->get('date_created_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDateCreated($date_created) {
    $this->set('date_created_ts', $date_created);
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
  public function getBid() {
    return $this->get('bid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBid($bid) {
    $this->set('bid', $bid);
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
  public function isPublished() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['date_created_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date Created'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('HID'))
      ->setDescription(t('The Hearing of the DD HearingAgenda entity'))
      ->setSettings(array(
        'target_type' => 'dd_hearing',
      ))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('BID'))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['current_flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Current Flag'))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
