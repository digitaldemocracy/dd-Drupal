<?php

namespace Drupal\dd_organization\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD OrganizationAlignment entity.
 *
 * @ingroup dd_organizationalignment
 *
 * @ContentEntityType(
 *   id = "dd_organizationalignment",
 *   label = @Translation("DD OrganizationAlignment"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_organization\Entity\DdOrganizationAlignmentViewsData",
 *   },
 *   base_table = "OrgAlignments",
 *   admin_permission = "administer dd orgalignment entities",
 *   entity_keys = {
 *     "id" = "oa_id",
 *     "label" = "alignment",
 *   },
 *   field_ui_base_route = "dd_organizationalignment.settings"
 * )
 */
class DdOrganizationAlignment extends DdBaseContentEntity implements DdOrganizationAlignmentInterface {
  /**
   * {@inheritdoc}
   */
  public function getOid() {
    return $this->get('oid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setOid($oid) {
    $this->set('oid', $oid);
    return $this;
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
   * {@inheritdoc}
   */
  public function getAlignment() {
    return $this->get('alignment')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAlignment($alignment) {
    $this->set('alignment', $alignment);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAnalysisFlag() {
    return $this->get('analysis_flag')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAnalysisFlag($analysis_flag) {
    $this->set('analysis_flag', $analysis_flag);
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
    $fields['oid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization ID'))
      ->setSetting('target_type', 'dd_hearing')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSetting('target_type', 'dd_hearing')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['alignment'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Alignment'))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['analysis_flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Analysis Flag'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
