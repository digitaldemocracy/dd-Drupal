<?php

namespace Drupal\dd_video\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_hearing\Entity\DdHearing;

/**
 * Defines the DD Video entity.
 *
 * @ingroup dd_video
 *
 * @ContentEntityType(
 *   id = "dd_video",
 *   label = @Translation("DD Video"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_video\DdVideoListBuilder",
 *     "views_data" = "Drupal\dd_video\Entity\DdVideoViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_video\Form\DdVideoForm",
 *       "edit" = "Drupal\dd_video\Form\DdVideoForm",
 *     },
 *     "access" = "Drupal\dd_video\DdVideoAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_video\DdVideoHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Video",
 *   admin_permission = "administer dd video entities",
 *   entity_keys = {
 *     "id" = "vid",
 *     "label" = "fileId",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_video/{dd_video}",
 *     "edit-form" = "/admin/structure/dd_video/{dd_video}/edit",
 *     "collection" = "/admin/structure/dd_video",
 *   },
 *   field_ui_base_route = "dd_video.settings"
 * )
 */
class DdVideo extends DdBaseStateField implements DdVideoInterface {

  /**
   * Get Video IDs for a hearing.
   *
   * @param int $hid
   *   Hearing ID to query for.
   *
   * @return array
   *   Array of Video Ids.
   */
  public static function getVideoFileIdsForHearingId($hid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Video', 'v');
    $query->fields('v', ['fileId']);
    $query->condition('v.hid', $hid);
    $query->orderBy('position');
    return $query->execute()->fetchCol();
  }
  /**
   * {@inheritdoc}
   */
  public function getFileId() {
    return $this->get('fileId')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFileId($file_id) {
    $this->set('fileId', $file_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHid() {
    return $this->get('hid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setHid($hid) {
    $this->set('hid', $hid);
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
  public function getStartOffset() {
    return $this->get('startOffset')->value;
  }

  /**
   * @inheritDoc
   */
  public function setStartOffset($start_offset) {
    $this->set('startOffset', $start_offset);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDuration() {
    return $this->get('duration')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDuration($duration) {
    $this->set('duration', $duration);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSrtFlag() {
    return $this->get('srtFlag')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSrtFlag($srt_flag) {
    $this->set('srtFlag', $srt_flag);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSource($source) {
    $this->set('source', $source);
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

    $fields['fileId'] = BaseFieldDefinition::create('string')
      ->setLabel(t('File ID'))
      ->setDescription(t('File ID of the video'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSetting('target_type', 'dd_hearing')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['position'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Position'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['startOffset'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Start Offset'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['duration'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Duration'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['srtFlag'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Srt Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
