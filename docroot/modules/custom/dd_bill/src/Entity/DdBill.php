<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD Bill entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill",
 *   label = @Translation("DD Bill"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_bill\DdBillListBuilder",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_bill\Form\DdBillForm",
 *       "edit" = "Drupal\dd_bill\Form\DdBillForm",
 *     },
 *     "access" = "Drupal\dd_bill\DdBillAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_bill\DdBillHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Bill",
 *   admin_permission = "administer dd bill entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *   },
 *   links = {
 *     "canonical" = "/bill/dd_id/{dd_bill}",
 *     "edit-form" = "/admin/structure/dd_bill/{dd_bill}/edit",
 *     "collection" = "/admin/structure/dd_bill",
 *   },
 *   field_ui_base_route = "dd_bill.settings"
 * )
 */
class DdBill extends DdBaseStateField implements DdBillInterface {
  /*
   * NOTE: If adding to billTypes, you must also update allowed choices
   * in dd_bill_alert.field_bill_type widget settings, otherwise
   * /node/add/dd_bill_alert will throw error on bill type.
   */
  private static $billTypes = [
    'CA' => [
      'AB' => 'AB',
      'ABX1' => 'ABX1',
      'ABX2' => 'ABX2',
      'ACA' => 'ACA',
      'ACR' => 'ACR',
      'ACRX2' => 'ACRX2',
      'AJR' => 'AJR',
      'BUD' => 'BUD',
      'HR' => 'HR',
      'SB' => 'SB',
      'SBX1' => 'SBX1',
      'SBX2' => 'SBX2',
      'SCA' => 'SCA',
      'SCAX1' => 'SCAX1',
      'SCR' => 'SCR',
      'SCRX1' => 'SCRX1',
      'SCRX2' => 'SCRX2',
      'SJR' => 'SJR',
      'SR' => 'SR',
      'SRX1' => 'SRX1',
      'SRX2' => 'SRX2',
      ],
    'NY' => [
      'A' => 'A',
      'B' => 'B',
      'C' => 'C',
      'E' => 'E',
      'J' => 'J',
      'K' => 'K',
      'L' => 'L',
      'R' => 'R',
      'S' => 'S',
      ],
    'FL' => [
      'HB' => 'HB',
      'HCR' => 'HCR',
      'HJR' => 'HJR',
      'HM' => 'HM',
      'HR' => 'HR',
      'SB' => 'SB',
      'SCR' => 'SCR',
      'SJR' => 'SJR',
      'SM' => 'SM',
      'SPB' => 'SPB',
      'SR' => 'SR',
    ],
    'TX' => [
      'HB' => 'HB',
      'HCR' => 'HCR',
      'HJR' => 'HJR',
      'HR' => 'HR',
      'SB' => 'SB',
      'SCR' => 'SCR',
      'SJR' => 'SJR',
      'SR' => 'SR',
    ],
  ];

  /**
   * Get Bill Types for state.
   *
   * @param string $state
   *   State, or current state if empty.
   *
   * @return array
   *   Array of bill types.
   */
  public static function getBillTypes($state = '') {
    if ($state == '') {
      return self::$billTypes[DdBase::getCurrentState()];
    }
    else {
      return self::$billTypes[$state];
    }
  }

  /**
   * Get BillVersionCurrent for bid.
   *
   * @param string $bid
   *   Bill ID
   *
   * @return object
   *   BillVersionCurrent object w/dr_id and vid properties.
   */
  public static function getBillVersionCurrentForBid($bid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('BillVersionCurrent', 'bvc');
    $query->fields('bvc', ['dr_id', 'vid']);
    $query->condition('bvc.bid', $bid);
    return $query->execute()->fetchObject();
  }

  /**
   * Get dr_id field from given bid.
   *
   * @param string $bid
   *   Bill ID
   *
   * @return object
   *   Bill object with dr_id property.
   */
  public static function getBillbyBid($bid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Bill', 'b');
    $query->fields('b', ['dr_id']);
    $query->condition('b.state', DdBase::getCurrentState());
    $query->condition('b.bid', $bid);
    return $query->execute()->fetchObject();
  }


  /**
   * Get bill entity from given bid.
   *
   * @param string $bid
   *   Bill ID
   *
   * @return object
   *   Bill entity object
   */
  public static function getByBid($bid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Bill', 'b');
    $query->fields('b', ['dr_id']);
    $query->condition('b.state', DdBase::getCurrentState());
    $query->condition('b.bid', $bid);

    $dr_id = $query->execute()->fetchField();
    if (!empty($dr_id)) {
      return self::load($dr_id);
    }
  }

  /**
   * Get Primary Bill Author PID for Bid/Vid.
   *
   * @param string $bid
   *   Bill ID
   * @param string $vid
   *   Version ID
   *
   * @return int
   *   PID of author
   */
  public static function getPrimaryBillAuthorPidForBid($bid) {
    $results = self::getBillAuthorsPidForBid($bid);
    if ($results) {
      return $results[0];
    }
    return NULL;
  }
  /**
   * Get Primary Bill Author PID for Bid/Vid.
   *
   * @param string $bid
   *   Bill ID
   *
   * @return int
   *   PID of author
   */
  public static function getBillAuthorsPidForBid($bid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('authors', 'a');
    $query->fields('a', ['pid']);
    $query->condition('a.bid', $bid);
    $query->groupby('a.pid');
    return $query->execute()->fetchCol();
  }

  /**
   * Get Bill Matches for a term.
   *
   * @param string $term
   *   Term to search by (ie. AB159)
   * @param int $num_matches
   *   Max # of matches to return.
   * @param string $state
   *   State override, if empty uses current state.
   * @param array $session_years
   *   Filter by session years
   *
   * @return array
   *   Array of Bill objects.
   */
  public static function getBillMatches($term, $num_matches = 4, $state = '', $session_years = []) {
    if ($state == '') {
      $state = DdBase::getCurrentState();
    }

    $bills = [];

    // Check Bill Match pattern.
    if (preg_match("/([a-zA-Z]+)\\s?([0-9]?+)|([0-9]+)/", $term, $bill_matches) && count($bill_matches) > 2) {
      // @todo evaluate security of $term.
      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Bill', 'b')
        ->where('CONCAT(b.type, b.number) LIKE :term OR CONCAT(b.type, \' \', b.number) LIKE :term', ['term' => '%' . $term . '%'])
        ->condition('b.state', $state);
      $query->join('BillVersionCurrent', 'bvc', 'bvc.bid = b.bid');
      if ($session_years && $session_years[0] != 'all') {
        $query->condition('b.sessionYear', $session_years, 'IN');
      }

      $query->fields('b', array('dr_id', 'type', 'number', 'bid', 'sessionYear'));
      $query->fields('bvc', array('subject'));
      $query->range(0, $num_matches);
      $query->orderBy('b.number');
      $query->orderBy('b.type');
      $query->orderBy('b.sessionYear', 'DESC');
      $bills = $query->execute()->fetchAll();

    }

    return $bills;
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
  public function getNumber() {
    return $this->get('number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNumber($number) {
    $this->set('number', $number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBillState() {
    return $this->get('billState')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBillState($bill_state) {
    $this->set('billState', $bill_state);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStatus() {
    return $this->get('status')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStatus($status) {
    $this->set('status', $status);
    return $this;
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
  public function getSession() {
    return $this->get('session')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSession($session) {
    $this->set('session', $session);
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
   * @inheritDoc
   */
  public function getBillVersionCurrentDrId() {
    return $this->get('bill_version_current_dr_id')->value;
  }

  /**
   * @inheritDoc
   */
  public function setBillVersionCurrentDrId($dr_id) {
    $this->set('bill_version_current_dr_id', $dr_id);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPrimaryAuthorPid() {
    return $this->get('primary_author_pid')->value;
  }

  /**
   * @inheritDoc
   */
  public function setPrimaryAuthorPid($pid) {
    $this->set('primary_author_pid', $pid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPrimaryAuthorFirstName() {
    return $this->get('primary_author_first_name')->value;
  }

  /**
   *
   * @inheritDoc
   */
  public function setPrimaryAuthorFirstName($name) {
    $this->set('primary_author_first_name', $name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPrimaryAuthorLastName() {
    return $this->get('primary_author_last_name')->value;
  }

  /**
   * @inheritDoc
   */
  public function setPrimaryAuthorLastName($name) {
    $this->set('primary_author_last_name', $name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPrimaryAuthorFirstLastName() {
    return $this->get('primary_author_first_last_name')->value;
  }

  /**
   * @inheritDoc
   */
  public function setPrimaryAuthorFirstLastName($name) {
    $this->set('primary_author_first_last_name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypeNumber() {
    return $this->get('type_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTypeNumber($type_number) {
    $this->set('type_number', $type_number);
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
    $label = '';
    $bill_version_id = self::getBillVersionCurrentForBid($this->getBid());
    if ($bill_version_id) {
      $bill_version = DdBillVersionCurrent::load($bill_version_id->dr_id);
      if ($bill_version) {
        $label = $this->getType() . ' ' . $this->getNumber() . ': ' . $bill_version->getSubject();
      }
    }
    return $label;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['billState'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill State'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sessionYear'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Below fields used for search API.
    $fields['bill_version_current_dr_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Version Current Drupal ID'))
      ->setSettings(array(
        'target_type' => 'dd_bill_version_current',
      ))
      ->setComputed(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['primary_author_pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Primary Author PID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setComputed(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['type_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill Type / Number'))
      ->setComputed(TRUE)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }
}
