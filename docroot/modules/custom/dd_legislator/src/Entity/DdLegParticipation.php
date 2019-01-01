<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Term entity.
 *
 * @ingroup dd_legislator
 *
 * @ContentEntityType(
 *   id = "dd_leg_participation",
 *   label = @Translation("DD LegParticipation"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_legislator\Entity\DdLegParticipationViewsData",
 *
 *     "access" = "Drupal\dd_legislator\DdLegislatorAccessControlHandler",
 *   },
 *   base_table = "LegParticipation",
 *   admin_permission = "administer dd legislator entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "bid",
 *   },
 * )
 */
class DdLegParticipation extends DdBaseContentEntity implements DdLegParticipationInterface {
  /**
   * @inheritDoc
   */
  public function getHid() {
    return $this->get('hid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setHid($hid) {
    $this->set('hid', $hid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDid() {
    return $this->get('did')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setDid($did) {
    $this->set('did', $did);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getBid() {
    return $this->get('bid');
  }

  /**
   * @inheritDoc
   */
  public function setBid($bid) {
    $this->set('bid', $bid);
    return $this;
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
   * @inheritDoc
   */
  public function getParty() {
    return $this->get('party');
  }

  /**
   * @inheritDoc
   */
  public function setParty($party) {
    $this->set('party', $party);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegBillWordCount() {
    return $this->get('LegBillWordCount');
  }

  /**
   * @inheritDoc
   */
  public function setLegBillWordCount($leg_bill_word_count) {
    $this->set('LegBillWordCount', $leg_bill_word_count);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegBillTime() {
    return $this->get('LegBillTime');
  }

  /**
   * @inheritDoc
   */
  public function setLegBillTime($leg_bill_time) {
    $this->set('LegBillTime', $leg_bill_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegBillPercentWord() {
    return $this->get('LegBillPercentWord');
  }

  /**
   * @inheritDoc
   */
  public function setLegBillPercentWord($leg_bill_percent_word) {
    $this->set('LegBillPercentWord', $leg_bill_percent_word);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegBillPercentTime() {
    return $this->get('LegBillPercentTime');
  }

  /**
   * @inheritDoc
   */
  public function setLegBillPercentTime($leg_bill_percent_time) {
    $this->set('LegBillPercentTime', $leg_bill_percent_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegHearingWordCount() {
    return $this->get('LegHearingWordCount');
  }

  /**
   * @inheritDoc
   */
  public function setLegHearingWordCount($leg_hearing_word_count) {
    $this->set('LegHearingWordCount', $leg_hearing_word_count);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegHearingTime() {
    return $this->get('LegHearingWordCount');
  }

  /**
   * @inheritDoc
   */
  public function setLegHearingTime($leg_hearing_time) {
    $this->set('LegHearingTime', $leg_hearing_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegHearingPercentWord() {
    return $this->get('LegHearingPercentWord');
  }

  /**
   * @inheritDoc
   */
  public function setLegHearingPercentWord($leg_hearing_percent_word) {
    $this->set('LegHearingPercentWord', $leg_hearing_percent_word);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegHearingPercentTime() {
    return $this->get('LegHearingPercentTime');
  }

  /**
   * @inheritDoc
   */
  public function setLegHearingPercentTime($leg_hearing_percent_time) {
    $this->set('LegHearingPercentTime', $leg_hearing_percent_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegHearingAvg() {
    return $this->get('LegHearingAvg');
  }

  /**
   * @inheritDoc
   */
  public function setLegHearingAvg($leg_hearing_avg) {
    $this->set('LegHearingAvg', $leg_hearing_avg);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getBillWordCount() {
    return $this->get('BillWordCount');
  }

  /**
   * @inheritDoc
   */
  public function setBillWordCount($bill_word_count) {
    $this->set('BillWordCount', $bill_word_count);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getHearingWordCount() {
    return $this->get('HearingWordCount');
  }

  /**
   * @inheritDoc
   */
  public function setHearingWordCount($hearing_word_count) {
    $this->set('HearingWordCount', $hearing_word_count);
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
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('BillDiscussion ID'))
      ->setSettings(array(
        'target_type' => 'dd_bill_discussion',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

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
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['party'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Party'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['LegBillWordCount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Legislative Bill Word Count'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegBillTime'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Legislative Bill Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegBillPercentWord'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Legislative Bill Percent Word'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegBillPercentTime'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Legislative Bill Percent Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegHearingWordCount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Legislative Hearing Word Count'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegHearingTime'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Legislative Hearing Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegHearingPercentWord'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Legislative Hearing Percent Word'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegHearingPercentTime'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Legislative Hearing Percent Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['LegHearingAvg'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Legislative Hearing Avg'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['BillWordCount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Bill Word Count'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['HearingWordCount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Hearing Word Count'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);
    return $fields;
  }
}
