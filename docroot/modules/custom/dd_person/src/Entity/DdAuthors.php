<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD Authors entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_authors",
 *   label = @Translation("DD Authors"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_person\DdAuthorsListBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdAuthorsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_person\Form\DdAuthorsForm",
 *       "add" = "Drupal\dd_person\Form\DdAuthorsForm",
 *       "edit" = "Drupal\dd_person\Form\DdAuthorsForm",
 *       "delete" = "Drupal\dd_person\Form\DdAuthorsDeleteForm",
 *     },
 *     "access" = "Drupal\dd_person\DdAuthorsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_person\DdAuthorsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "authors",
 *   admin_permission = "administer dd authors entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "vid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_authors/{dd_authors}",
 *     "add-form" = "/admin/structure/dd_authors/add",
 *     "edit-form" = "/admin/structure/dd_authors/{dd_authors}/edit",
 *     "delete-form" = "/admin/structure/dd_authors/{dd_authors}/delete",
 *     "collection" = "/admin/structure/dd_authors",
 *   },
 *   field_ui_base_route = "dd_authors.settings"
 * )
 */
class DdAuthors extends DdPersonContentEntityBase implements DdAuthorsInterface {
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
  public function getVid() {
    return $this->get('vid')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVid($vid) {
    $this->set('vid', $vid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getContribution() {
    return $this->get('contribution')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setContribution($contribution) {
    $this->set('contribution', $contribution);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBidDrId() {
    return $this->get('bid_dr_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBidDrId($bid_dr_id) {
    $this->set('bid_dr_id', $bid_dr_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVidDrId() {
    return $this->get('vid_dr_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVidDrId($vid_dr_id) {
    $this->set('vid_dr_id', $vid_dr_id);
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
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
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

    $fields['bid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['vid'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bill Version ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['contribution'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Contribution'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'inline',
        'type' => 'string',
        'weight' => 30,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
