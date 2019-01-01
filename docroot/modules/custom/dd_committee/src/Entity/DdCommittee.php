<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_admin\DdAdmin;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD Committee entity.
 *
 * @ingroup dd_committee
 *
 * @ContentEntityType(
 *   id = "dd_committee",
 *   label = @Translation("DD Committee"),
 *   handlers = {
 *     "storage" = "Drupal\dd_committee\Entity\Sql\DdCommitteeSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_committee\DdCommitteeListBuilder",
 *     "views_data" = "Drupal\dd_committee\Entity\DdCommitteeViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_committee\Form\DdCommitteeForm",
 *       "edit" = "Drupal\dd_committee\Form\DdCommitteeForm",
 *     },
 *     "access" = "Drupal\dd_committee\DdCommitteeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_committee\DdCommitteeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Committee",
 *   admin_permission = "administer dd committee entities",
 *   entity_keys = {
 *     "id" = "cid",
 *     "label" = "name"
 *   },
 *   links = {
 *     "canonical" = "/committee_id/{dd_committee}",
 *     "edit-form" = "/admin/structure/dd_committee/{dd_committee}/edit",
 *     "collection" = "/admin/structure/dd_committee",
 *   },
 *   field_ui_base_route = "dd_committee.settings"
 * )
 */
class DdCommittee extends DdBaseStateField implements DdCommitteeInterface {
  /**
   * Get Hearing IDs for a committee ID.
   *
   * @param int $cid
   *   Committee ID.
   *
   * @return array
   *   Array of hearing iDs.
   */
  public static function getHearingIdsForCommittee($cid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CommitteeHearings', 'ch');
    $query->fields('ch', ['hid']);
    $query->condition('ch.cid', $cid);
    return $query->execute()->fetchCol();
  }

  /**
   * Get Committee Matches for a term.
   *
   * @param string $term
   *   Term to search by (ie. Joint Committee On Arts)
   * @param int $num_matches
   *   Max # of matches to return.
   * @param string $state
   *   State override, if empty uses current state.
   *
   * @return array
   *   Array of Bill objects.
   */
  public static function getCommitteeMatches($term, $num_matches = 4, $state = '') {
    if ($state == '') {
      $state = DdBase::getCurrentState();
    }
    // @todo evaluate security of $term.
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c')
      ->condition('c.name', '%' . $term . '%', 'LIKE')
      ->condition('c.state', $state);
    $query->fields('c', array('name', 'type'));
    $query->join('CommitteeNames', 'cn', 'c.name = cn.name and c.house = cn.house and c.state = cn.state');
    $query->addField('cn', 'cn_id');

    $query->condition('c.type', 'Floor', '!=');

    if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      $config = \Drupal::config(DdAdmin::getDdAdminContentSettingsName());
      $committees_include_exclude = $config->get('committees_include_exclude');
      $cn_ids = $config->get('committee_cn_ids');

      if ($cn_ids) {
        $query->condition('cn_id', $cn_ids, $committees_include_exclude ? 'IN' : 'NOT IN');
      }
    }

    $query->range(0, $num_matches);
    $query->orderBy('c.name');
    $query->distinct(TRUE);
    return $query->execute()->fetchAll();
  }

  /**
   * Load Committee from Committee Name ID.
   *
   * @param int $cn_id
   *   Committee Name ID.
   *
   * @return DdCommittee
   *   DdCommittee entity.
   */
  public static function loadCommitteeByNameId($cn_id) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c');
    $query->fields('c', array('cid'));
    $query->join('CommitteeNames', 'cn', 'c.name = cn.name and c.house = cn.house and c.state = cn.state');
    $query->addField('cn', 'cn_id');
    $query->condition('cn.cn_id', $cn_id);

    // Get only the most current committee.
    $query->orderBy('c.session_year', 'DESC');
    $query->range(0, 1);
    $cid = $query->execute()->fetchField();
    if ($cid) {
      return self::load($cid);
    }
    return NULL;
  }

  /**
   * Hierarchical listing of committees.
   *
   * @param bool $use_cn_id_value
   *   If TRUE, uses cn_id value instead of committee name.
   * @param bool $exclude_floor
   *   if TRUE, will exclude floor sessions.
   *
   * @return array
   *   Array of committee options.
   */
  public static function buildCommitteeList($use_cn_id_value = FALSE, $exclude_floor = TRUE) {
    // Get the data.
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c');
    $query->fields('c', ['house', 'type', 'name']);
    $query->condition('c.state', DdBase::getCurrentState());
    if ($exclude_floor) {
      $query->condition('c.type', 'Floor', '!=');
    }

    $query->join('CommitteeNames', 'cn', 'c.name = cn.name and c.house = cn.house and c.state = cn.state');
    $query->addField('cn', 'cn_id');
    if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      $config = \Drupal::config(DdAdmin::getDdAdminContentSettingsName());
      $committees_include_exclude = $config->get('committees_include_exclude');
      $cn_ids = $config->get('committee_cn_ids');

      if ($cn_ids) {
        $query->condition('cn_id', $cn_ids, $committees_include_exclude ? 'IN' : 'NOT IN');
      }
    }

    $query->orderBy('c.house');
    $query->orderBy('c.type');
    $query->orderBy('c.name');
    $query->distinct(TRUE);
    $committees = $query->execute()->fetchAll();

    if ($exclude_floor) {
      $committee_options = ['' => 'Select a Committee'];
    }
    else {
      $committee_options = ['' => 'Select Location'];
    }
    foreach ($committees as $committee) {
      // Define group.
      $group = $committee->house . ' ' . $committee->type;
      if ($committee->house == 'Joint') {
        $group = 'Joint';
      }

      // Check if group exists.
      if (!array_key_exists($group, $committee_options)) {
        $committee_options[$group] = [];
      }

      // Populate option.
      $value = $use_cn_id_value ? $committee->cn_id : $committee->name;
      $committee_options[$group][$value] = $committee->name;
    }
    return $committee_options;
  }

  /**
   * List of committees for current state, un-grouped.
   *
   * @param bool $exclude_floor
   *   if TRUE, will exclude floor sessions.
   *
   * @return array
   *   Array of committee options.
   */
  public static function buildCommitteeOptions($exclude_floor = TRUE) {
    $committee_options = [];

    // Get the data.
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c');
    $query->fields('c', ['house', 'type', 'name']);
    $query->condition('c.state', DdBase::getCurrentState());
    if ($exclude_floor) {
      $query->condition('c.type', 'Floor', '!=');
    }

    $query->join('CommitteeNames', 'cn', 'c.name = cn.name and c.house = cn.house and c.state = cn.state');
    $query->addField('cn', 'cn_id');

    $query->orderBy('c.name');
    $query->orderBy('c.house');
    $query->orderBy('c.type');
    $query->distinct(TRUE);
    $committees = $query->execute()->fetchAll();

    foreach ($committees as $committee) {
      $committee_options[$committee->cn_id] = $committee->name;
    }
    return $committee_options;
  }

  /**
   * Get Committee IDs for a Committee Name ID.
   *
   * @param array $cn_ids
   *   Committee Name IDs
   *
   * @return array
   *   Array of CIDs.
   */
  public static function getCommitteeIdsForCommitteeNameId(array $cn_ids) {
    // Get the data.
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c');
    $query->fields('c', ['cid']);
    $query->condition('c.state', DdBase::getCurrentState());
    $query->join('CommitteeNames', 'cn', 'c.name = cn.name and c.house = cn.house and c.state = cn.state');
    $query->condition('cn.cn_id', $cn_ids, 'IN');
    $cids = $query->execute()->fetchCol();
    return $cids;

  }

  /**
   * Get Active Committees.
   *
   * @param string $house
   *   Optional House (Senate, Assembly)
   *
   * @return array
   *   Array of Committees.
   */
  public static function getActiveCommittees($house = '') {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Committee', 'c');
    $query->fields('c', ['cid', 'name']);
    $query->condition('c.state', DdBase::getCurrentState());
    $query->condition('c.current_flag', 1);
    $query->condition('c.type', 'Floor', '!=');
    if (!empty($house)) {
      $query->condition('c.house', $house);
    }
    $query->orderBy('c.name');
    $committees = $query->execute()->fetchAll();
    return $committees;
  }

  /**
   * {@inheritdoc}
   */
  public function getHouse() {
    return $this->get('house')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHouse($house) {
    $this->set('house', $house);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->get('type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setType($type) {
    $this->set('type', $type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getShortName() {
    return $this->get('short_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setShortName($short_name) {
    $this->set('short_name', $short_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHouseType() {
    return $this->get('house_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHouseType($house_type) {
    $this->set('house_type', $house_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHids() {
    return $this->get('hids')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHids($hids) {
    $this->set('hids', $hids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVids() {
    return $this->get('vids')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVids($vids) {
    $this->set('vids', $vids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommitteeAuthors() {
    return $this->get('committee_authors')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCommitteeAuthors($ids) {
    $this->set('committee_authors', $ids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoom() {
    return $this->get('room')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRoom($room) {
    $this->set('room', $room);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhone() {
    return $this->get('phone')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPhone($phone) {
    $this->set('phone', $phone);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFax() {
    return $this->get('fax')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFax($fax) {
    $this->set('fax', $fax);
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
  public function getIsCurrent() {
    return $this->get('current_flag')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsCurrent($current_flag) {
    $this->set('current_flag', $current_flag);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommitteeNameId() {
    return $this->get('cn_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCommitteeNameId($cn_id) {
    $this->set('cn_id', $cn_id);
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

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setDescription(t('The house of the DD Committee entity.'))
      ->setSettings(array(
        'max_length' => 200,
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
      ->setDisplayConfigurable('view', TRUE);


    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the DD Committee entity.'))
      ->setSettings(array(
        'max_length' => 200,
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

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type of the DD Committee entity.'))
      ->setSettings(array(
        'max_length' => 100,
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
      ->setDisplayConfigurable('view', TRUE);

    $fields['short_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Short Name'))
      ->setDescription(t('The short name of the DD Committee entity.'))
      ->setSettings(array(
        'max_length' => 100,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 60,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 60,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('HIDs'))
      ->setDescription(t('The HIDs of the DD Committe entity'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'target_type' => 'dd_hearing',
      ))
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['committee_authors'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee Authors'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'target_type' => 'dd_committee_authors',
      ))
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['house_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House Type'))
      ->setDescription(t('The house type of the DD Committee entity.'))
      ->setSettings(array(
        'max_length' => 200,
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
      ->setDisplayConfigurable('view', TRUE)
      ->setComputed(TRUE);

    $fields['room'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Room'))
      ->setDescription(t('The Room of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setDescription(t('The Phone of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['fax'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Fax'))
      ->setDescription(t('The Fax of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Email'))
      ->setDescription(t('The Email of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDescription(t('The Session Year of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['current_flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Current Flag'))
      ->setDescription(t('Is Current flag of the DD Committee entity.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cn_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Committee Name ID'))
      ->setDescription(t('Committee Name ID'))
      ->setComputed(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
