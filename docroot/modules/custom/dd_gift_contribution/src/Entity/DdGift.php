<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD Gift entity.
 *
 * @ingroup dd_gift_contribution
 *
 * @ContentEntityType(
 *   id = "dd_gift",
 *   label = @Translation("DD Gift"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_gift_contribution\DdGiftListBuilder",
 *     "views_data" = "Drupal\dd_gift_contribution\Entity\DdGiftViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_gift_contribution\Form\DdGiftForm",
 *       "edit" = "Drupal\dd_gift_contribution\Form\DdGiftForm",
 *     },
 *     "access" = "Drupal\dd_gift_contribution\DdGiftAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_gift_contribution\DdGiftHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Gift",
 *   admin_permission = "administer dd gift entities",
 *   entity_keys = {
 *     "id" = "RecordID",
 *     "label" = "description",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_gift/{dd_gift}",
 *     "edit-form" = "/admin/structure/dd_gift/{dd_gift}/edit",
 *     "collection" = "/admin/structure/dd_gift",
 *   },
 *   field_ui_base_route = "dd_gift.settings"
 * )
 */
class DdGift extends DdPersonContentEntityBase implements DdGiftInterface {
  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return TRUE;
  }

  /**
   * @inheritDoc
   */
  public function getSchedule() {
    return $this->get('schedule')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSchedule($schedule) {
    $this->set('schedule', $schedule);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSourceName() {
    return $this->get('sourceName')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSourceName($source_name) {
    $this->set('source_name', $source_name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getActivity() {
    return $this->get('activity')->value;
  }

  /**
   * @inheritDoc
   */
  public function setActivity($activity) {
    $this->set('activity', $activity);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getCity() {
    return $this->get('city')->value;
  }

  /**
   * @inheritDoc
   */
  public function setCity($city) {
    $this->set('city', $city);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getCityState() {
    return $this->get('cityState')->value;
  }

  /**
   * @inheritDoc
   */
  public function setCityState($city_state) {
    $this->set('cityState', $city_state);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getValue() {
    return $this->get('value')->value;
  }

  /**
   * @inheritDoc
   */
  public function setValue($value) {
    $this->set('value', $value);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getGiftDate() {
    return $this->get('giftDate_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setGiftDate($gift_date) {
    $this->set('giftDate_ts', $gift_date);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSessionYear() {
    return $this->get('sessionYear')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSessionYear($session_year) {
    $this->set('sessionYear', $session_year);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getReimbursed() {
    return $this->get('reimbursed')->value;
  }

  /**
   * @inheritDoc
   */
  public function setReimbursed($reimbursed) {
    $this->set('reimbursed', $reimbursed);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getGiftIncomeFlag() {
    return $this->get('giftIncomeFlag')->value;
  }

  /**
   * @inheritDoc
   */
  public function setGiftIncomeFlag($gift_income_flag) {
    $this->set('giftIncomeFlag', $gift_income_flag);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSpeechFlag() {
    return $this->get('speechFlag')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSpeechFlag($speech_flag) {
    $this->set('speech_flag', $speech_flag);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getOid() {
    return $this->get('oid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setOid($oid) {
    $this->set('oid', $oid);
    return $this;
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

    $fields['schedule'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Schedule'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sourceName'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source Name'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['activity'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Activity'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cityState'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City State'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['value'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Value'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['giftDate_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Gift Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sessionYear'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['reimbursed'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Reimbursed'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['giftIncomeFlag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Gift Income Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['speechFlag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Speech Flag'))
      ->setDescription(t('Speech Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
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
    return $fields;
  }

}
