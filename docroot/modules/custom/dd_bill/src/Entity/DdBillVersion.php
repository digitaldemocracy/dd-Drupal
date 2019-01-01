<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD Bill Version entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill_version",
 *   label = @Translation("DD Bill Version"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_bill\DdBillVersionListBuilder",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillVersionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_bill\Form\DdBillVersionForm",
 *       "edit" = "Drupal\dd_bill\Form\DdBillVersionForm",
 *     },
 *     "access" = "Drupal\dd_bill\DdBillVersionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_bill\DdBillVersionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "BillVersion",
 *   admin_permission = "administer dd bill version entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "vid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_bill_version/{dd_bill_version}",
 *     "edit-form" = "/admin/structure/dd_bill_version/{dd_bill_version}/edit",
 *     "collection" = "/admin/structure/dd_bill_version",
 *   },
 *   field_ui_base_route = "dd_bill_version.settings"
 * )
 */
class DdBillVersion extends DdBaseStateField implements DdBillVersionInterface {
  /**
   * @inheritDoc
   */
  public function getVid() {
    return $this->get('vid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setVid($vid) {
    $this->set('vid', $vid);
  }

  /**
   * @inheritDoc
   */
  public function getBid() {
    return $this->get('bid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setBid($bid) {
    $this->set('bid', $bid);
  }

  /**
   * @inheritDoc
   */
  public function getDate() {
    return $this->get('date_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDate($date) {
    $this->set('date_ts', $date);
  }

  /**
   * @inheritDoc
   */
  public function getBillState() {
    return $this->get('billState')->value;
  }

  /**
   * @inheritDoc
   */
  public function setBillState($bill_state) {
    $this->set('billState', $bill_state);
  }

  /**
   * @inheritDoc
   */
  public function getSubject() {
    return $this->get('subject')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSubject($subject) {
    $this->set('subject', $subject);
  }

  /**
   * @inheritDoc
   */
  public function getAppropriation() {
    return $this->get('appropriation')->value;
  }

  /**
   * @inheritDoc
   */
  public function setAppropriation($appropriation) {
    $this->set('appropriation', $appropriation);
  }

  /**
   * @inheritDoc
   */
  public function getSubstantiveChanges() {
    return $this->get('substantive_changes')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSubstantiveChanges($substantive_changes) {
    $this->set('substantive_changes', $substantive_changes);
  }

  /**
   * @inheritDoc
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * @inheritDoc
   */
  public function setTitle($title) {
    $this->set('title', $title);
  }

  /**
   * @inheritDoc
   */
  public function getDigest() {
    return $this->get('digest')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDigest($digest) {
    $this->set('digest', $digest);
  }

  /**
   * @inheritDoc
   */
  public function getText() {
    return $this->get('text')->value;
  }

  /**
   * @inheritDoc
   */
  public function setText($text) {
    $this->set('text', $text);
  }

  /**
   * {@inheritdoc}
   */
  public function getBidDrId() {
    return $this->get('bid_dr_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBidDrId($bid_dr_id) {
    $this->set('bid_dr_id', $bid_dr_id);
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

    $fields['vid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Version ID'))
      ->setSettings(array(
        'max_length' => 33,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setSettings(array(
        'max_length' => 23,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['billState'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill State'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['subject'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subject'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['appropriation'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Appropriation'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['substantive_changes'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Substantive Changes'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['digest'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Digest'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Text'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['digest'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Digest'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
