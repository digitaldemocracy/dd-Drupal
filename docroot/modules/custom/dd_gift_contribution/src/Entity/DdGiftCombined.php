<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_legislator\Entity\DdLegislator;

/**
 * Defines the DD Gift entity.
 *
 * @ingroup dd_gift_contribution
 *
 * @ContentEntityType(
 *   id = "dd_gift_combined",
 *   label = @Translation("DD GiftCombined"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "views_data" = "Drupal\dd_gift_contribution\Entity\DdGiftCombinedViewsData",
 *     "access" = "Drupal\dd_gift_contribution\DdGiftAccessControlHandler",
 *   },
 *   base_table = "GiftCombined",
 *   entity_keys = {
 *     "id" = "RecordID",
 *     "label" = "description",
 *   }
 * )
 */
class DdGiftCombined extends DdBaseStateField implements DdGiftCombinedInterface {
  /**
   * Get Gift Session Years.
   *
   * @return array
   *   Options array.
   */
  public static function getSessionYears($source) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('GiftCombined', 'c');
    $query->fields('c', ['sessionYear']);
    $query->condition('c.state', DdBase::getCurrentState());
    $query->isNotNull('c.sessionYear');
    $query->orderBy('c.sessionYear', 'DESC');
    $query->distinct(TRUE);

    switch ($source) {
      case 'legislator':
        $query->isNotNull('c.legislatorPid');
        break;

      case 'staff':
        $query->isNull('c.legislatorPid');
        break;
    }

    $results = $query->execute();
    $options = array();
    foreach ($results as $record) {
      $options[$record->sessionYear] = $record->sessionYear . ' - ' . ($record->sessionYear + 1);
    }

    return $options;
  }

  /**
   * Get Gift Recipients.
   *
   * @param string $type
   *   Prepended to the options.
   * @param string $session_year
   *   Session year to filter by.
   *
   * @return array
   *   Options array.
   */
  public static function getRecipients($type = '', $session_year = '') {

    $options = array();
    $type_label = '';
    $options[''] = t('Choose a person');

    switch ($type) {
      case 'office':
        $options[''] = t('Choose an office');
        $type_label = 'Office of ';

        break;

      case 'staff':
        $options[''] = t('Choose a staff');
        break;

    }
    $results = self::getLegislatorRecipients($type, $session_year);

    foreach ($results as $record) {
      if ($type == 'staff') {
        $options[$record->recipientPid] = $type_label . $record->last . ', ' . $record->first . ' (Staff of ' . $record->lp_first . ' ' . $record->lp_last . ')';
      }
      else {
        if ($type == 'office') {
          $options[$record->legislatorPid] = $type_label . $record->last . ', ' . $record->first;
          if ($record->house != '') {
            $options[$record->legislatorPid] .= ' (' . $record->house . ' | ' . $record->party . ')';
          }
        }
        else {
          $options[$record->recipientPid] = $type_label . $record->last . ', ' . $record->first;
          if ($record->house != '') {
            $options[$record->recipientPid] .= ' (' . $record->house . ' | ' . $record->party . ')';
          }
        }


      }
    }

    return $options;
  }
  /**
   * Get Legislators with pid and term info.
   *
   * @param string $type
   *   Blank = legislator, staff, or office.
   * @param string $session_year
   *   Session year to filter by.
   *
   * @return array
   *   Array of results.
   */
  public static function getLegislatorRecipients($type = '', $session_year = '') {
    $query = Database::getConnection('default', DdBase::getDddbName())->select('GiftCombined', 'gc');

    switch ($type) {
      case '':
        $query->fields('gc', ['recipientPid']);
        $query->leftJoin('Person', 'p', 'p.pid = gc.recipientPid');
        $query->fields('p', ['first', 'last']);
        $query->orderBy('p.last', 'ASC');
        $query->isNull('gc.legislatorPid');
        if ($session_year != '') {
          $query->leftJoin('Term', 't', 't.pid = gc.recipientPid AND t.year = gc.sessionYear');
          $query->fields('t', ['house', 'party']);
        }
        break;

      case 'staff':
        $query->fields('gc', ['recipientPid', 'legislatorPid']);
        $query->leftJoin('Person', 'p', 'p.pid = gc.recipientPid');
        $query->fields('p', ['first', 'last']);
        $query->orderBy('p.last', 'ASC');
        $query->leftJoin('Person', 'lp', 'lp.pid = gc.legislatorPid');
        $query->fields('lp', ['first', 'last']);
        $query->isNotNull('gc.legislatorPid');
        if ($session_year != '') {
          $query->leftJoin('Term', 't', 't.pid = gc.legislatorPid AND t.year = gc.sessionYear');
          $query->fields('t', ['house', 'party']);
        }
        break;

      case 'office':
        $query->fields('gc', ['legislatorPid']);
        $query->leftJoin('Person', 'lp', 'lp.pid = gc.legislatorPid');
        $query->fields('lp', ['first', 'last']);
        $query->orderBy('lp.last', 'ASC');
        $query->isNotNull('gc.legislatorPid');
        if ($session_year != '') {
          $query->leftJoin('Term', 't', 't.pid = gc.legislatorPid AND t.year = gc.sessionYear');
          $query->fields('t', ['house', 'party']);
        }
        break;
    }


    if (!empty($session_year)) {
      $query->condition('gc.sessionYear', $session_year);
    }

    $query->isNotNull('gc.recipientPid');
    $query->condition('gc.state', DdBase::getCurrentState());
    $query->distinct(TRUE);
    $results = $query->execute()->fetchAll();
    return $results;
  }

  /**
   * @inheritDoc
   */
  public function getRecipientPid() {
    return $this->get('recipientPid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setRecipientPid($recipient_pid) {
    $this->set('recipientPid', $recipient_pid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getLegislatorPid() {
    return $this->get('legislatorPid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setLegislatorPid($legislator_pid) {
    $this->set('legislatorPid', $legislator_pid);
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
    $this->set('gift_date', $gift_date);
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
  public function getGiftValue() {
    return $this->get('giftValue')->value;
  }

  /**
   * @inheritDoc
   */
  public function setGiftValue($gift_value) {
    $this->set('giftValue', $gift_value);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getAgencyName() {
    return $this->get('agencyName')->value;
  }

  /**
   * @inheritDoc
   */
  public function setAgencyName($agency_name) {
    $this->set('agencyName', $agency_name);
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
    $this->set('sourceName', $source_name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSourceBusiness() {
    return $this->get('sourceBusiness')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSourceBusiness($source_business) {
    $this->set('sourceBusiness', $source_business);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSourceCity() {
    return $this->get('sourceCity')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSourceCity($sourceCity) {
    $this->set('sourceCity', $sourceCity);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSourceState() {
    return $this->get('sourceState')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSourceState($sourceState) {
    $this->set('sourceState', $sourceState);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getImageUrl() {
    return $this->get('imageUrl')->value;
  }

  /**
   * @inheritDoc
   */
  public function setImageUrl($image_url) {
    $this->set('imageUrl', $image_url);
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
  public function getJurisdiction() {
    return $this->get('jurisdiction')->value;
  }

  /**
   * @inheritDoc
   */
  public function setJurisdiction($jurisdiction) {
    $this->set('jurisdiction', $jurisdiction);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDistrictNumber() {
    return $this->get('districtNumber')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDistrictNumber($district_number) {
    $this->set('districtNumber', $district_number);
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
    $this->set('speechFlag', $speech_flag);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSpeechOrPanel() {
    return $this->get('speechOrPanel')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSpeechOrPanel($speech_or_panel) {
    $this->set('speechOrPanel', $speech_or_panel);
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
    $fields['recipientPid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Recipient PID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['legislatorPid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Legislator PID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
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

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['giftValue'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Gift Value'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['agencyName'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Agency Name'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sourceName'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source Name'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sourceBusiness'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source Business'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sourceCity'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source City'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sourceState'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source State'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['imageUrl'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Image URL'))
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

    $fields['activity'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Activity'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['position'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Position'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['schedule'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Schedule'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['jurisdiction'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Schedule'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['districtNumber'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('District Number'))
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['speechOrPanel'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Speech Or Panel'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
