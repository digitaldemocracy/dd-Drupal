<?php

namespace Drupal\dd_person\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;

/**
 * Defines the DD PersonClassifications entity.
 *
 * @ingroup dd_person
 *
 * @ContentEntityType(
 *   id = "dd_person_classifications",
 *   label = @Translation("DD Person Classifications"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\dd_person\Entity\DdPersonClassificationsViewsData"
 *   },
 *   base_table = "PersonClassifications",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "classification"
 *   },
 * )
 */
class DdPersonClassifications extends DdBaseStateField implements DdPersonClassificationsInterface {
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
  public function getPersonType() {
    return $this->get('PersonType')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPersonType($person_type) {
    $this->set('PersonType', $person_type);
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
  public function getSpecificYear() {
    return $this->get('specific_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSpecificYear($specific_year) {
    $this->set('specific_year', $specific_year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSessionYear() {
    return $this->get('session_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSessionYear($session_year) {
    $this->set('session_year', $session_year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIsCurrent() {
    return $this->get('is_current')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsCurrent($is_current) {
    $this->set('is_current', $is_current);
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
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['PersonType'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Person Type'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['specific_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Specific Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['is_current'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Is Current'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
