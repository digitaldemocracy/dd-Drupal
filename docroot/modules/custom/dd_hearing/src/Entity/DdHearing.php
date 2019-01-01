<?php

namespace Drupal\dd_hearing\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_committee\Entity\DdCommittee;

/**
 * Defines the DD Hearing entity.
 *
 * @ingroup dd_hearing
 *
 * @ContentEntityType(
 *   id = "dd_hearing",
 *   label = @Translation("DD Hearing"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_hearing\DdHearingListBuilder",
 *     "views_data" = "Drupal\dd_hearing\Entity\DdHearingViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_hearing\Form\DdHearingForm",
 *       "edit" = "Drupal\dd_hearing\Form\DdHearingForm",
 *     },
 *     "access" = "Drupal\dd_hearing\DdHearingAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_hearing\DdHearingHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Hearing",
 *   admin_permission = "administer dd hearing entities",
 *   entity_keys = {
 *     "id" = "hid",
 *   },
 *   links = {
 *     "canonical" = "/hearing/{dd_hearing}",
 *     "edit-form" = "/admin/structure/dd_hearing/{dd_hearing}/edit",
 *     "collection" = "/admin/structure/dd_hearing",
 *   },
 *   field_ui_base_route = "dd_hearing.settings"
 * )
 */
class DdHearing extends DdBaseStateField implements DdHearingInterface {
  /**
   * Get Committee IDs for a hearing ID.
   *
   * @param int $hid
   *   Hearing ID.
   *
   * @return array
   *   Array of committee iDs.
   */
  public static function getCommitteeIdsForHearing($hid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('CommitteeHearings', 'ch');
    $query->fields('ch', ['cid']);
    $query->condition('ch.hid', $hid);
    return $query->execute()->fetchCol();
  }

  /**
   * Get BillDiscussion IDs for a hearing ID.
   *
   * @param int $hid
   *   Hearing ID.
   *
   * @return array
   *   Array of BillDiscussion IDs.
   */
  public static function getBillDiscussionIdsForHearing($hid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('BillDiscussion', 'bd');
    $query->fields('bd', ['did']);
    $query->condition('bd.hid', $hid);
    return $query->execute()->fetchCol();
  }

  /**
   * Get Legislator Speaker PIDs for a hearing ID.
   *
   * @param int $hid
   *   Hearing ID.
   *
   * @return array
   *   Array of Legislator Speaker PIDs.
   */
  public static function getLegislatorSpeakerPidsForHearing($hid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('BillDiscussion', 'bd');
    $query->condition('bd.hid', $hid);
    $query->innerJoin('InitialUtterance', 'u', 'u.did = bd.did');
    $query->fields('u', ['pid']);
    $query->innerJoin('Legislator', 'l', 'l.pid = u.pid');
    $query->groupBy('u.pid');
    return $query->execute()->fetchCol();
  }

  /**
   * Get Speakers for a Hearing, first time a speaker talks.
   *
   * @param $hid
   *   Hearing ID
   * @param bool $get_pids_only
   *   If TRUE, will only return array of PIDs, otherwise object w/fields.
   *
   * @return array
   *   Array of field objects or array of PIDs.
   */
  public static function getSpeakersForHearing($hid, $get_pids_only = TRUE) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('InitialUtterance', 'iu');
    $query->join('Utterance', 'u', 'u.uid = iu.uid');
    $query->join('Video', 'v', 'v.vid = u.vid');
    $query->condition('v.hid', $hid);
    $query->condition('u.state', DdBase::getCurrentState());

    if ($get_pids_only) {
      $query->fields('iu', array('pid'));
      $query->groupBy('iu.pid');
      $pids = $query->execute()->fetchCol();
      return $pids;
    }

    $query->fields('v', array('fileId', 'hid'));
    $query->fields('u', array('time'));
    $query->join('Person', 'p', 'p.pid = iu.pid');
    $query->fields('p', array('pid', 'last', 'first'));
    $query->orderBy('p.last');
    $query->orderBy('u.time');
    $results = $query->execute()->fetchAll();


    // Remove all but 1st instances of speaker.
    $found_speakers = array();
    if ($results) {
      foreach ($results as $key => $value) {
        if (!isset($found_speakers[$value->pid])) {
          $found_speakers[$value->pid] = $value;
        }
      }
    }

    return $found_speakers;
  }

  /**
   * Get Hearing BillDiscussions IDs for Speakers, first time a speaker talks.
   *
   * @param int $hid
   *   Hearing ID
   * @param array $pids
   *   Array of Person IDs
   *
   * @return array
   *   Array of DIDs.
   */
  public static function getHearingBillDiscussionIdsForSpeaker($hid, $pids) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('InitialUtterance', 'iu');
    $query->join('Utterance', 'u', 'u.uid = iu.uid');
    $query->join('Video', 'v', 'v.vid = u.vid');
    $query->condition('v.hid', $hid);
    $query->condition('iu.pid', $pids, 'IN');
    $query->condition('u.state', DdBase::getCurrentState());

    $query->fields('iu', array('did'));
    $query->groupBy('iu.did');
    $dids = $query->execute()->fetchCol();
    return $dids;
  }

  /**
   * Get 1st video for hearing.
   *
   * @param int $hid
   *   Hearing ID
   *
   * @return object
   *   Video object w/fileId, startOffset.
   */
  public static function getInitialHearingVideo($hid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Video', 'v');
    $query->condition('v.hid', $hid);
    $query->fields('v', ['fileId', 'startOffset']);
    $query->orderBy('v.position');
    $query->orderBy('v.vid');
    $query->range(0, 1);
    $video = $query->execute()->fetchObject();
    return $video;

  }

  /**
   * Get Committee Hearing Matches for a term.
   *
   * @param string $term
   *   Term to search.
   *
   * @return array
   *   Array of Hearing IDs.
   */
  public static function getCommitteeHearingMatches($term, $limit = 10) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Hearing', 'h');
    $query->join('CommitteeHearings', 'ch', 'ch.hid = h.hid');
    $query->join('Committee', 'c', 'c.cid = ch.cid');
    $query->fields('h', ['hid']);
    $or = $query->orConditionGroup();
    $or->condition('c.name', '%' . $term . '%', 'LIKE');
    $or->where('DATE_FORMAT(h.date, \'%m-%d-%Y\') LIKE :term', [':term' => '%' . $term . '%']);
    $query->condition($or);
    $query->condition('h.state', DdBase::getCurrentState());
    $query->orderBy('c.name');
    $query->orderBy('h.date');
    $query->range(0, $limit);
    return $query->execute()->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function getDate() {
    return $this->get('date_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDate($date) {
    $this->set('date_ts', $date);
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
  public function setSessionYear($year) {
    $this->set('session_year', $year);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getCids() {
    return $this->get('cids')->value;
  }

  /**
   * @inheritDoc
   */
  public function setCids($cids) {
    $this->set('cids', $cids);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getCommitteeNameIds() {
    return $this->get('cn_ids')->value;
  }

  /**
   * @inheritDoc
   */
  public function setCommitteeNameIds($cn_ids) {
    $this->set('cn_ids', $cn_ids);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDids() {
    return $this->get('dids')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDids($dids) {
    $this->set('dids', $dids);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    // Return unpublished status if no video for hearing.
    $video = self::getInitialHearingVideo($this->id());
    return (!empty($video));
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    $label = '';
    $committee_ids = self::getCommitteeIdsForHearing($this->id());
    if ($committee_ids) {
      $committees = DdCommittee::loadMultiple($committee_ids);
      if ($committees) {
        $committee_names = [];
        foreach ($committees as $committee) {
          $committee_names[] = $committee->getName();
        }
        $label = implode(', ', $committee_names) . ' Hearing of ' . date('m-d-Y', $this->getDate());
      }
    }
    return $label;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('CIDs'))
      ->setDescription(t('The Committee IDs of the DD Hearing entity'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'target_type' => 'dd_committee',
      ))
      ->setDisplayOptions('view', array(
        'weight' => 20,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 20,
      ))
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['cn_ids'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Committee Name IDs'))
      ->setDescription(t('Committee Name IDs'))
      ->setComputed(TRUE)
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['dids'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('DIDs'))
      ->setDescription(t('The BillDiscussion IDs of the DD Hearing entity'))
      ->setComputed(TRUE)
      ->setSettings(array(
        'target_type' => 'dd_bill_discussion',
      ))
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }

}
