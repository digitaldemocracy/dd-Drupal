<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_base\DdBase;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_person\Entity\DdPersonContentEntityBase;

/**
 * Defines the DD Contribution entity.
 *
 * @ingroup dd_gift_contribution
 *
 * @ContentEntityType(
 *   id = "dd_contribution",
 *   label = @Translation("DD Contribution"),
 *   handlers = {
 *     "storage" = "Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_gift_contribution\DdContributionListBuilder",
 *     "views_data" = "Drupal\dd_gift_contribution\Entity\DdContributionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_gift_contribution\Form\DdContributionForm",
 *       "edit" = "Drupal\dd_gift_contribution\Form\DdContributionForm",
 *     },
 *     "access" = "Drupal\dd_gift_contribution\DdContributionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_gift_contribution\DdContributionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Contribution",
 *   admin_permission = "administer dd contribution entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "donorName",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_contribution/{dd_contribution}",
 *     "edit-form" = "/admin/structure/dd_contribution/{dd_contribution}/edit",
 *     "collection" = "/admin/structure/dd_contribution",
 *   },
 *   field_ui_base_route = "dd_contribution.settings"
 * )
 */
class DdContribution extends DdPersonContentEntityBase implements DdContributionInterface {
  /**
   * Get Contribution Recipients.
   *
   * @param string $type
   *   Prepended to the options.
   *
   * @return array
   *   Options array.
   */
  public static function getRecipients($type = '') {
    $results = DdLegislator::getLegislatorsPidWithTermInfo();

    $options = array();
    $options[''] = t('Choose a person');

    switch ($type) {
      case 'office':
        $options[''] = t('Choose an office');
        $type = 'Office of ';
        break;

      case 'staff':
        $options[''] = t('Choose a staff');
        $type = 'Staff of ';
        break;

    }

    foreach ($results as $record) {
      $options[$record->pid] = $type . $record->last . ', ' . $record->first . ' (' . $record->house . ' | ' . $record->party . ')';
    }

    return $options;
  }

  /**
   * @inheritDoc
   */
  public function getId() {
    return $this->get('id')->value;
  }

  /**
   * @inheritDoc
   */
  public function setId($id) {
    $this->set('id', $id);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getYear() {
    return $this->get('year')->value;
  }

  /**
   * @inheritDoc
   */
  public function setYear($year) {
    $this->set('year', $year);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDate() {
    return $this->get('date_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDate($date) {
    $this->set('date_ts', $date);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getHouse() {
    return $this->get('house')->value;
  }

  /**
   * @inheritDoc
   */
  public function setHouse($house) {
    $this->set('house', $house);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDonorName() {
    return $this->get('donorName')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDonorName($donor_name) {
    $this->set('donorName', $donor_name);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDonorOrg() {
    return $this->get('donorOrg')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDonorOrg($donorOrg) {
    $this->set('donorOrg', $donorOrg);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getAmount() {
    return $this->get('amount')->value;
  }

  /**
   * @inheritDoc
   */
  public function setAmount($amount) {
    $this->set('amount', $amount);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getOid() {
    return $this->get('oid')->getValue();
  }

  /**
   * @inheritDoc
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
    $fields['id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('ID'))
      ->setDescription(t('ID'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person ID'))
      ->setDescription(t('Person ID'))
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
      ->setDescription(t('Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['date_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date'))
      ->setDescription(t('Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['house'] = BaseFieldDefinition::create('string')
      ->setLabel(t('House'))
      ->setDescription(t('House'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['donorName'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Donor Name'))
      ->setDescription(t('Donor Name'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['donorOrg'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Donor Org'))
      ->setDescription(t('Donor Org'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['amount'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Amount'))
      ->setDescription(t('Amount'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['oid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Organization ID'))
      ->setDescription(t('Organization ID'))
      ->setSettings(array(
        'target_type' => 'dd_organization',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
