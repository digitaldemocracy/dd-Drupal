<?php

namespace Drupal\dd_clip\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;
use Drupal\user\UserInterface;

/**
 * Defines the DD Clip entity.
 *
 * @ingroup dd_clip
 *
 * @ContentEntityType(
 *   id = "dd_clip",
 *   label = @Translation("DD Clip"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_clip\DdClipListBuilder",
 *     "views_data" = "Drupal\dd_clip\Entity\DdClipViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_clip\Form\DdClipForm",
 *       "add" = "Drupal\dd_clip\Form\DdClipForm",
 *       "edit" = "Drupal\dd_clip\Form\DdClipForm",
 *       "delete" = "Drupal\dd_clip\Form\DdClipDeleteForm",
 *     },
 *     "access" = "Drupal\dd_clip\DdClipAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_clip\DdClipHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "dd_clip",
 *   admin_permission = "administer dd clip entities",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/dd_clip/{dd_clip}",
 *     "add-form" = "/admin/structure/dd_clip/add",
 *     "edit-form" = "/dd_clip/{dd_clip}/edit",
 *     "delete-form" = "/dd_clip/{dd_clip}/delete",
 *     "collection" = "/admin/structure/dd_clip",
 *   },
 *   field_ui_base_route = "dd_clip.settings"
 * )
 */
class DdClip extends DdBaseContentEntity implements DdClipInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

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
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the DD Clip entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the DD Clip entity.'))
      ->setSettings(array(
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Shared'))
      ->setDescription(t('Uncheck to make this clip private.'))
      ->setDefaultValue(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored By'))
      ->setDescription(t('The author of this clip.'))
      ->setSetting('target_type', 'user')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['campaign_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign'))
      ->setDescription(t('Campaign'))
      ->setSetting('target_type', 'node')
      ->setComputed(TRUE);

    $fields['action_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign Action'))
      ->setDescription(t('Campaign Action'))
      ->setSetting('target_type', 'paragraph')
      ->setComputed(TRUE);

    return $fields;
  }

}
