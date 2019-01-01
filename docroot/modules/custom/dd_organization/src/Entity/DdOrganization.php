<?php

namespace Drupal\dd_organization\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Organization entity.
 *
 * @ingroup dd_organization
 *
 * @ContentEntityType(
 *   id = "dd_organization",
 *   label = @Translation("DD Organization"),
 *   handlers = {
 *     "storage" = "Drupal\dd_organization\Entity\Sql\DdOrganizationSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_organization\DdOrganizationListBuilder",
 *     "views_data" = "Drupal\dd_organization\Entity\DdOrganizationViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_organization\Form\DdOrganizationForm",
 *       "edit" = "Drupal\dd_organization\Form\DdOrganizationForm",
 *     },
 *     "access" = "Drupal\dd_organization\DdOrganizationAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_organization\DdOrganizationHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Organizations",
 *   admin_permission = "administer dd organization entities",
 *   entity_keys = {
 *     "id" = "oid",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/organization/{dd_organization}",
 *     "edit-form" = "/admin/structure/dd_organization/{dd_organization}/edit",
 *     "collection" = "/admin/structure/dd_organization",
 *   },
 *   field_ui_base_route = "dd_organization.settings"
 * )
 */
class DdOrganization extends DdBaseContentEntity implements DdOrganizationInterface {
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
  public function getCity() {
    return $this->get('city')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCity($city) {
    $this->set('city', $city);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStateHeadquartered() {
    return $this->get('stateHeadquartered')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStateHeadquartered($state_headquartered) {
    $this->set('stateHeadquartered', $state_headquartered);
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
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 20,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['stateHeadquartered'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State Headquartered'))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 30,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Type'))
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'number_integer',
        'weight' => 40,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
