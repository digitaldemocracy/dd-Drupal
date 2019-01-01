<?php

namespace Drupal\dd_legislator\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;
use Drupal\user\UserInterface;

/**
 * Defines the DD Term entity.
 *
 * @ingroup dd_legislator
 *
 * @ContentEntityType(
 *   id = "dd_term",
 *   label = @Translation("DD Term"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_legislator\DdTermListBuilder",
 *     "views_data" = "Drupal\dd_legislator\Entity\DdTermViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_legislator\Form\DdTermForm",
 *       "edit" = "Drupal\dd_legislator\Form\DdTermForm",
 *     },
 *     "access" = "Drupal\dd_legislator\DdTermAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_legislator\DdTermHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Term",
 *   admin_permission = "administer dd term entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "pid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_term/{dd_term}",
 *     "edit-form" = "/admin/structure/dd_term/{dd_term}/edit",
 *     "collection" = "/admin/structure/dd_term",
 *   },
 *   field_ui_base_route = "dd_term.settings"
 * )
 */
class DdTerm extends DdPersonContentEntityBase implements DdTermInterface {
  /**
   * Get Terms for Legislator Pids.
   *
   * Used primarily for hearing page.
   *
   * @param array $pids
   *   Array of Person IDs.
   * @param bool $current_term_only
   *   If TRUE, returns only current terms.
   *
   * @return array
   *   Array of Terms fields.
   */
  public static function getTermsForLegislatorPids($pids, $current_term_only = FALSE) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Term', 't');
    $query->fields('t', ['pid', 'year', 'district', 'party', 'house']);
    $query->condition('t.pid', $pids, 'IN');

    if ($current_term_only) {
      $query->condition('t.current_term', 1);
    }
    $query->innerJoin('Person', 'p', 't.pid = p.pid');
    $query->fields('p', ['first', 'last']);
    $query->leftJoin('District', 'd', 'd.did = t.district AND d.house = t.house AND d.state = t.state');
    $query->addExpression('CONCAT(p.first, \' \' , p.last)', 'name');
    $query->fields('d', ['region']);
    $query->orderBy('t.year');
    $terms = $query->execute()->fetchAllAssoc('pid');
    return $terms;
  }

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
  public function getYear() {
    return $this->get('year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setYear($year) {
    $this->set('year', $year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDistrict() {
    return $this->get('district')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDistrict($district) {
    $this->set('district', $district);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getHouse() {
    return $this->get('house')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setHouse($house) {
    $this->set('house', $house);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getParty() {
    return $this->get('party')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setParty($party) {
    $this->set('party', $party);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getStartDate() {
    return $this->get('start_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setStartDate($start_date) {
    $this->set('start_ts', $start_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEndDate() {
    return $this->get('end_ts')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEndDate($end_date) {
    $this->set('end_ts', $end_date);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCaucus() {
    return $this->get('caucus')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCaucus($caucus) {
    $this->set('caucus', $caucus);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentTerm() {
    return $this->get('current_term')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCurrentTerm($current_term) {
    $this->set('current_term', $current_term);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getOfficialBio() {
    return $this->get('official_bio')->value;
  }

  /**
   * @inheritDoc
   */
  public function setOfficialBio($official_bio) {
    $this->set('official_bio', $official_bio);
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
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['district'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('District'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['party'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Party'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['start_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Start Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['end_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('End Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['caucus'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Caucus'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['current_term'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Current Term'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['official_bio'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Official Bio'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'text',
        'weight' => 60,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'text_textarea',
        'weight' => 60,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setReadOnly(TRUE);

    return $fields;

  }
}
