<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Payor entity.
 *
 * @ingroup dd_gift_contribution
 *
 * @ContentEntityType(
 *   id = "dd_payor",
 *   label = @Translation("DD Payor"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_gift_contribution\DdPayorListBuilder",
 *     "views_data" = "Drupal\dd_gift_contribution\Entity\DdPayorViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_gift_contribution\Form\DdPayorForm",
 *       "edit" = "Drupal\dd_gift_contribution\Form\DdPayorForm",
 *     },
 *     "access" = "Drupal\dd_gift_contribution\DdPayorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_gift_contribution\DdPayorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Payors",
 *   admin_permission = "administer dd payor entities",
 *   entity_keys = {
 *     "id" = "prid",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_payor/{dd_payor}",
 *     "edit-form" = "/admin/structure/dd_payor/{dd_payor}/edit",
 *     "collection" = "/admin/structure/dd_payor",
 *   },
 *   field_ui_base_route = "dd_payor.settings"
 * )
 */
class DdPayor extends DdBaseContentEntity implements DdPayorInterface {

  /**
   * @inheritDoc
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * @inheritDoc
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getCity() {
    return $this->get('city')->value;
  }

  /**
   * @inheritDoc
   */
  public function setCity($city) {
    $this->set('city', $city);
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
