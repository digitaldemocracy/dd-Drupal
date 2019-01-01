<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD Speaker Participation entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_speaker_participation",
 *   label = @Translation("DD Speaker Participation"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdSpeakerParticipationViewsData"
 *   },
 *   base_table = "SpeakerParticipation",
 *   admin_permission = "administer dd person entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "pid",
 *   },
 * )
 */
class DdSpeakerParticipation extends DdBaseStateField implements DdSpeakerParticipationInterface {

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
  public function getWordCountTotal() {
    return $this->get('WordCountTotal')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWordCountTotal($word_count_total) {
    $this->set('WordCountTotal', $word_count_total);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWordCountHearingAvg() {
    return $this->get('WordCountHearingAvg')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWordCountHearingAvg($word_count_hearing_avg) {
    $this->set('WordCountHearingAvg', $word_count_hearing_avg);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeTotal() {
    return $this->get('TimeTotal')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTimeTotal($time_total) {
    $this->set('TimeTotal', $time_total);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTimeHearingAvg() {
    return $this->get('TimeHearingAverage')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTimeHearingAvg($time_hearing_avg) {
    $this->set('TimeHearingAverage', $time_hearing_avg);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBillDiscussionCount() {
    return $this->get('BillDiscussionCount')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBillDiscussionCount($bill_discussion_count) {
    $this->set('BillDiscussionCount', $bill_discussion_count);
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
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['session_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['WordCountTotal'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Word Count Total'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['WordCountHearingAvg'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Word Count Hearing Avg'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['TimeTotal'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Time Total'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['TimeHearingAvg'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Time Hearing Avg'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['BillDiscussionCount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Bill Discussion Count'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
