<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;
use Drupal\user\UserInterface;

/**
 * Defines the DD Legislative Staff entity.
 *
 * @ingroup dd_legislator
 *
 * @ContentEntityType(
 *   id = "dd_legislative_staff",
 *   label = @Translation("DD Legislative Staff"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_legislator\DdLegislativeStaffListBuilder",
 *     "views_data" = "Drupal\dd_legislator\Entity\DdLegislativeStaffViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_legislator\Form\DdLegislativeStaffForm",
 *       "edit" = "Drupal\dd_legislator\Form\DdLegislativeStaffForm",
 *     },
 *     "access" = "Drupal\dd_legislator\DdLegislativeStaffAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_legislator\DdLegislativeStaffHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "LegislativeStaff",
 *   admin_permission = "administer dd legislative staff entities",
 *   entity_keys = {
 *     "id" = "pid",
 *     "label" = "pid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_legislative_staff/{dd_legislative_staff}",
 *     "edit-form" = "/admin/structure/dd_legislative_staff/{dd_legislative_staff}/edit",
 *     "collection" = "/admin/structure/dd_legislative_staff",
 *   },
 *   field_ui_base_route = "dd_legislative_staff.settings"
 * )
 */
class DdLegislativeStaff extends DdPersonContentEntityBase implements DdLegislativeStaffInterface {
  /**
   * {@inheritdoc}
   */
  public function getFlag() {
    return $this->get('flag')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFlag($flag) {
    $this->set('flag', $flag);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLegislator() {
    return $this->get('legislator')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setLegislator($legislator) {
    $this->set('legislator', $legislator);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommittee() {
    return $this->get('committee')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setCommittee($committee) {
    $this->set('committee', $committee);
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
    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['legislator'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Legislator ID'))
      ->setSetting('target_type', 'dd_legislator')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['flag'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Is Committee'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['committee'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Committee ID'))
      ->setSetting('target_type', 'dd_committee')
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }
}
