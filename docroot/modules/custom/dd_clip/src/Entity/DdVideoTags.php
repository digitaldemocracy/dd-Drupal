<?php

namespace Drupal\dd_clip\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\dd_base\Entity\DdBaseContentEntity;

/**
 * Defines the DD Video Tags entity.
 *
 * @ingroup dd_clip
 *
 * @ContentEntityType(
 *   id = "dd_video_tags",
 *   label = @Translation("DD Video Tags"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_clip\DdVideoTagsListBuilder",
 *     "views_data" = "Drupal\dd_clip\Entity\DdVideoTagsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_clip\Form\DdVideoTagsForm",
 *       "add" = "Drupal\dd_clip\Form\DdVideoTagsForm",
 *       "edit" = "Drupal\dd_clip\Form\DdVideoTagsForm",
 *       "delete" = "Drupal\dd_clip\Form\DdVideoTagsDeleteForm",
 *     },
 *     "access" = "Drupal\dd_clip\DdVideoTagsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_clip\DdVideoTagsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "dd_video_tags",
 *   admin_permission = "administer dd video tags entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "tag",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_video_tags/{dd_video_tags}",
 *     "add-form" = "/admin/structure/dd_video_tags/add",
 *     "edit-form" = "/admin/structure/dd_video_tags/{dd_video_tags}/edit",
 *     "delete-form" = "/admin/structure/dd_video_tags/{dd_video_tags}/delete",
 *     "collection" = "/admin/structure/dd_video_tags",
 *   },
 *   field_ui_base_route = "dd_video_tags.settings"
 * )
 */
class DdVideoTags extends DdBaseContentEntity implements DdVideoTagsInterface {

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
  public function getTag() {
    return $this->get('tag')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTag($tag) {
    $this->set('tag', $tag);
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
      ->setDescription(t('The user ID of author of the DD Video Tags entity.'))
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

    $fields['tag'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Tag'))
      ->setDescription(t('The tag of the DD Video Tags entity.'))
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
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the DD Video Tags is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
