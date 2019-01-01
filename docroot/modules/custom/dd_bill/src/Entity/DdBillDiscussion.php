<?php

namespace Drupal\dd_bill\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Bill Discussion entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill_discussion",
 *   label = @Translation("DD Bill Discussion"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_bill\DdBillDiscussionListBuilder",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillDiscussionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_bill\Form\DdBillDiscussionForm",
 *       "edit" = "Drupal\dd_bill\Form\DdBillDiscussionForm",
 *     },
 *     "access" = "Drupal\dd_bill\DdBillDiscussionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_bill\DdBillDiscussionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "BillDiscussion",
 *   admin_permission = "administer dd bill discussion entities",
 *   entity_keys = {
 *     "id" = "did",
 *     "label" = "bid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_bill_discussion/{dd_bill_discussion}",
 *     "edit-form" = "/admin/structure/dd_bill_discussion/{dd_bill_discussion}/edit",
 *     "collection" = "/admin/structure/dd_bill_discussion",
 *   },
 *   field_ui_base_route = "dd_bill_discussion.settings"
 * )
 */
class DdBillDiscussion extends DdBaseContentEntity implements DdBillDiscussionInterface {


  /**
   * Get Speakers for a BillDiscussion, first time a speaker talks.
   *
   * @param did
   *   BillDiscussion ID
   * @param bool $get_pids_only
   *   If TRUE, will only return array of PIDs, otherwise object w/fields.
   *
   * @return array
   *   Array of field objects or array of PIDs.
   */
  public static function getSpeakersForBillDiscussion($did, $get_pids_only = TRUE) {
    $results = array();

    if ($did) {
      $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('InitialUtterance', 'iu');
      $query->join('Utterance', 'u', 'u.uid = iu.uid');
      $query->condition('u.did', $did);
      $query->condition('u.state', DdBase::getCurrentState());

      if ($get_pids_only) {
        $query->fields('iu', array('pid'));
        $pids = $query->execute()->fetchCol();
        return $pids;
      }

      $query->condition('p.first', 'Committee', '!=');
      $query->condition('p.first', 'Unidentified', '!=');

      $query->fields('u', array('time'));
      $query->join('Video', 'v', 'v.vid = u.vid');
      $query->fields('v', array('fileId', 'hid'));
      $query->join('Person', 'p', 'p.pid = iu.pid');
      $query->fields('p', array('last', 'first'));
      $query->orderBy('p.last');
      $results = $query->execute()->fetchAll();
    }

    return $results;
  }

  public static function getSpeakerStartUtterance($did, $pid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('InitialUtterance', 'iu');
    $query->join('Utterance', 'u', 'u.uid = iu.uid');
    $query->condition('iu.pid', $pid);
    $query->condition('iu.did', $did);
    $query->fields('u', array('time', 'endTime', 'vid', 'uid'));
    $utterance = $query->execute()->fetchObject();
    return $utterance;
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
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getBidDrId() {
    return $this->get('bid_dr_id')->value;
  }

  /**
   * @inheritDoc
   */
  public function setBidDrId($bid_dr_id) {
    $this->set('bid_dr_id', $bid_dr_id);
    return $this;
  }

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
  public function getStartVideo() {
    return $this->get('startVideo')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setStartVideo($start_video) {
    $this->set('startVideo', $start_video);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getStartTime() {
    return $this->get('startTime')->value;
  }

  /**
   * @inheritDoc
   */
  public function setStartTime($start_time) {
    $this->set('startTime', $start_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getEndVideo() {
    return $this->get('endVideo')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setEndVideo($end_video) {
    $this->set('endVideo', $end_video);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getEndTime() {
    return $this->get('endTime')->value;
  }

  /**
   * @inheritDoc
   */
  public function setEndTime($end_time) {
    $this->set('endTime', $end_time);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getNumVideos() {
    return $this->get('numVideos')->value;
  }

  /**
   * @inheritDoc
   */
  public function setNumVideos($num_videos) {
    $this->set('numVideos', $num_videos);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSpeakers() {
    return $this->get('speakers')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setSpeakers($speakers) {
    $this->set('speakers', $speakers);
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
    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['bid_dr_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Drupal ID'))
      ->setSetting('target_type', 'dd_bill')
      ->setComputed(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSetting('target_type', 'dd_hearing')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['startVideo'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Start Video'))
      ->setSetting('target_type', 'dd_video')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['startTime'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Start Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['endVideo'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('End Video'))
      ->setSetting('target_type', 'dd_video')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['endTime'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('End Time'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['numVideos'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number of Videos'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['speakers'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Speakers'))
      ->setSetting('target_type', 'dd_person')
      ->setComputed(TRUE)
      ->setCardinality(-1)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
