<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD General Public entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_general_public",
 *   label = @Translation("DD General Public"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_person\DdGeneralPublicListBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdGeneralPublicViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_person\Form\DdGeneralPublicForm",
 *       "edit" = "Drupal\dd_person\Form\DdGeneralPublicForm",
 *     },
 *     "access" = "Drupal\dd_person\DdGeneralPublicAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_person\DdGeneralPublicHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "GeneralPublic",
 *   admin_permission = "administer dd general public entities",
 *   entity_keys = {
 *     "id" = "RecordId",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_general_public/{dd_general_public}",
 *     "edit-form" = "/admin/structure/dd_general_public/{dd_general_public}/edit",
 *     "collection" = "/admin/structure/dd_general_public",
 *   },
 *   field_ui_base_route = "dd_general_public.settings"
 * )
 */
class DdGeneralPublic extends DdPersonContentEntityBase implements DdGeneralPublicInterface {
  /**
   * {@inheritdoc}
   */
  public function getPid() {
    return $this->get('pid')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setPid($pid) {
    $this->set('pid', $pid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPosition() {
    return $this->get('position')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPosition($position) {
    $this->set('position', $position);
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
  public function getDid() {
    return $this->get('did')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setDid($did) {
    $this->set('did', $did);
    return $this;
  }

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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['position'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Position'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['hid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Hearing ID'))
      ->setSetting('target_type', 'dd_hearing')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['did'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Bill Discussion ID'))
      ->setSetting('target_type', 'dd_bill_discussion')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['oid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization ID'))
      ->setSetting('target_type', 'dd_organization')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
