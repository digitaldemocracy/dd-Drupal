<?php

namespace Drupal\dd_committee\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD CommitteeHearings entity.
 *
 * @ingroup dd_committee
 *
 * @ContentEntityType(
 *   id = "dd_committee_hearings",
 *   label = @Translation("DD Committee Hearings"),
 *   handlers = {
 *     "storage" = "Drupal\dd_committee\Entity\Sql\DdCommitteeHearingsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_committee\Entity\DdCommitteeHearingsViewsData",
 *     "access" = "Drupal\dd_committee\DdCommitteeAccessControlHandler",
 *   },
 *   base_table = "CommitteeHearings",
 *   admin_permission = "administer dd committee entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "cid"
 *   },
 * )
 */
class DdCommitteeHearings extends DdBaseContentEntity implements DdCommitteeHearingsInterface {
  /**
   * @inheritDoc
   */
  public function getCid() {
    return $this->get('cid')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setCid($cid) {
    $this->set('cid', $cid);
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

    $fields['cid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('CID'))
      ->setDescription(t('The CID of the DD CommitteeHearing entity'))
      ->setSettings(array(
        'target_type' => 'dd_committee',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('HID'))
      ->setDescription(t('The HID of the DD CommitteeHearing entity'))
      ->setSettings(array(
        'target_type' => 'dd_hearing',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
