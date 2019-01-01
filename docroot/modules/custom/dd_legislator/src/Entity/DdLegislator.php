<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD Legislator entity.
 *
 * @ingroup dd_legislator
 *
 * @ContentEntityType(
 *   id = "dd_legislator",
 *   label = @Translation("DD Legislator"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_legislator\DdLegislatorListBuilder",
 *     "views_data" = "Drupal\dd_legislator\Entity\DdLegislatorViewsData",
 *     "translation" = "Drupal\dd_legislator\DdLegislatorTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_legislator\Form\DdLegislatorForm",
 *       "edit" = "Drupal\dd_legislator\Form\DdLegislatorForm",
 *     },
 *     "access" = "Drupal\dd_legislator\DdLegislatorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_legislator\DdLegislatorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Legislator",
 *   translatable = FALSE,
 *   admin_permission = "administer dd_legislator entities",
 *   entity_keys = {
 *     "id" = "pid",
 *   },
 *   links = {
 *     "canonical" = "/legislator/{dd_legislator}",
 *     "edit-form" = "/admin/structure/dd_legislator/{dd_legislator}/edit",
 *     "collection" = "/admin/structure/dd_legislator",
 *   },
 *   field_ui_base_route = "dd_legislator.settings"
 * )
 */
class DdLegislator extends DdPersonContentEntityBase implements DdLegislatorInterface {
  /**
   * Get Terms for PID.
   *
   * @param int $pid
   *   PID to get terms for.
   * @param bool $current_only
   *   Get Current Term Only.
   *
   * @return array
   *   Array of term fields.
   */
  public static function getTerms($pid, $current_only = FALSE) {
    $query = Database::getConnection('default', DdBase::getDddbName())->select('Term', 't');
    $query->fields('t', ['year', 'district', 'house', 'party', 'start', 'end', 'state', 'caucus']);
    $query->condition('t.pid', $pid);
    $query->orderBy('year', 'DESC');

    if ($current_only) {
      $query->condition('current_term', 1);
      $query->range(0, 1);
    }
    $results = $query->execute()->fetchAll();
    return $results;
  }

  /**
   * Get Legislators with pid and term info.
   * @return array
   *   Array of results.
   */
  public static function getLegislatorsPidWithTermInfo() {
    $query = Database::getConnection('default', DdBase::getDddbName())->select('Legislator', 'l');
    $query->fields('l', ['pid']);
    $query->join('Person', 'p', 'p.pid = l.pid');
    $query->fields('p', ['first', 'last']);
    $query->join('Term', 't', 't.pid = l.pid');
    $query->fields('t', ['house', 'year', 'party']);
    $query->condition('l.state', DdBase::getCurrentState());
    $query->orderBy('p.last', 'ASC');
    $query->orderBy('t.year', 'DESC');
    $query->distinct(TRUE);
    $results = $query->execute()->fetchAll();
    return $results;
  }

  /**
   * Get Current Legislators with optional filters.
   *
   * @param string $house
   *   House (Assembly / Senate)
   * @param string $party
   *   Party (Republican / Democrat)
   * @param string $committee_name
   *   Committee Name
   */
  public static function getCurrentLegislators($house = '', $party = '', $committee_name = '') {
    $query = Database::getConnection('default', DdBase::getDddbName())->select('Legislator', 'l');
    $query->fields('l', ['pid']);
    $query->join('Person', 'p', 'p.pid = l.pid');
    $query->fields('p', ['first', 'last']);
    $query->join('Term', 't', 't.pid = l.pid');
    $query->fields('t', ['house', 'party', 'district']);
    $query->condition('l.state', DdBase::getCurrentState());
    $query->condition('t.current_term', 1);

    if (!empty($house)) {
      $query->condition('t.house', $house);
    }

    if (!empty($party)) {
      $query->condition('t.party', $party);
    }

    if ($committee_name != '') {
      $query->join('servesOn', 'so', 'so.pid = l.pid');
      $query->condition('so.current_flag', 1);
      $query->join('Committee', 'c', 'so.cid = c.cid');
      $query->condition('c.name', $committee_name);
    }

    $query->orderBy('p.last', 'ASC');
    $results = $query->execute()->fetchAllAssoc('pid');
    return $results;
  }

  /**
   * Get Legislator Entity By District.
   *
   * @param int $district
   *   District number
   * @param string $house
   *   House (Assembly / Sentate)
   *
   * @return DdLegislator
   *   Legislator Entity.
   */
  public static function getLegislatorByDistrict($district, $house) {
    $params = [
      ['field' => 'current_term', 'value' => 1],
      ['field' => 'house', 'value' => $house],
      [
        'field' => 'district',
        'value' => $district,
      ],
      ['field' => 'state', 'value' => DdBase::getCurrentState()],
    ];
    $terms = DdTerm::loadByFields($params);
    $term = reset($terms);
    $pid = $term->getPid()[0]['target_id'];
    return DdLegislator::load($pid);
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTwitterHandle() {
    return $this->get('twitter_handle')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTwitterHandle($twitter_handle) {
    $this->set('twitter_handle', $twitter_handle);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCapitolPhone() {
    return $this->get('capitol_phone')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCapitolPhone($capitol_phone) {
    $this->set('capitol_phone', $capitol_phone);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWebsiteURL() {
    return $this->get('website_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWebsiteURL($websiteURL) {
    $this->set('website_url', $websiteURL);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoomNumber() {
    return $this->get('room_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRoomNumber($room_number) {
    $this->set('room_number', $room_number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmailFormLink() {
    return $this->get('email_form_link')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmailFormLink($email_form_link) {
    $this->set('email_form_link', $email_form_link);
    return $this;
  }
  /**
   * {@inheritdoc}
   */
  public function getCapitolFax() {
    return $this->get('capitol_fax')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCapitolFax($capitol_fax) {
    $this->set('capitol_fax', $capitol_fax);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($email) {
    $this->set('email', $email);
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
  public function label() {
    $terms = self::getTerms($this->id());
    $term = $terms[0];
    $party_letter = strtoupper(substr($term->party, 0, 1));
    return $this->getFirstName() . ' ' . $this->getLastName() . ' [' . $term->house . ' - ' . $party_letter  . ']';
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

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('The description of the DD Legislator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 10,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);


    $fields['twitter_handle'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Twitter Handle'))
      ->setDescription(t('The twitter handle of the DD Legislator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 20,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 20,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);


    $fields['capitol_phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Capitol Phone'))
      ->setDescription(t('The capitol phone of the DD Legislator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 30,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 30,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['website_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Website URL'))
      ->setDescription(t('The website url of the DD Legislator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 40,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 40,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);


    $fields['room_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Room Number'))
      ->setDescription(t('The room number of the DD Legislator entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['email_form_link'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email Form Link'))
      ->setDescription(t('The email form link of the DD Legislator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 50,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 50,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['capitol_fax'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Capitol Fax'))
      ->setDescription(t('The capitol fax of the DD Legislator entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
}
