<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD Campaign Visitor entity.
 *
 * @ingroup dd_metrics
 *
 * @ContentEntityType(
 *   id = "dd_campaign_visitor",
 *   label = @Translation("DD Campaign Visitor"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "storage_schema" = "Drupal\dd_metrics\DdMetricsStorageSchema",
 *     "views_data" = "Drupal\dd_metrics\Entity\DdCampaignVisitorViewsData",
 *     "access" = "Drupal\dd_metrics\DdCampaignVisitorAccessControlHandler",
 *   },
 *   base_table = "dd_campaign_visitor",
 *   admin_permission = "administer dd action metrics entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "created",
 *     "uid" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/dd_campaign_visitor/{dd_campaign_visitor}",
 *   },
 *   field_ui_base_route = "dd_campaign_visitor.settings"
 *
 * )
 */
class DdCampaignVisitor extends ContentEntityBase implements DdCampaignVisitorInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getCampaignId() {
    return $this->get('campaign_id')->getValue() ? $this->get('campaign_id')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setCampaignId($id) {
    $this->set('campaign_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFirstName() {
    return $this->get('first_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstName($first_name) {
    $this->set('first_name', $first_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastName() {
    return $this->get('last_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastName($last_name) {
    $this->set('last_name', $last_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEmail($last_name) {
    $this->set('email', $last_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAddress() {
    return $this->get('address')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAddress($address) {
    $this->set('address', $address);
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
  public function getState() {
    return $this->get('state')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setState($state) {
    $this->set('state', $state);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getZip() {
    return $this->get('zip')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setZip($zip) {
    $this->set('zip', $zip);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCounty() {
    return $this->get('county')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCounty($county) {
    $this->set('county', $county);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAssemblyDistrict() {
    return $this->get('assembly_district')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setAssemblyDistrict($assembly_district) {
    $this->set('assembly_district', $assembly_district);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSenateDistrict() {
    return $this->get('senate_district')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSenateDistrict($senate_district) {
    $this->set('senate_district', $senate_district);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSessionId() {
    return $this->get('session_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSessionId($session_id) {
    $this->set('session_id', $session_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getUserId() {
    return $this->get('uid')->getValue() ? $this->get('uid')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setUserId($user_id) {
    $this->set('uid', $user_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWhitelabelId() {
    return $this->get('whitelabel_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWhitelabelId($whitelabel_id) {
    $this->set('whitelabel_id', $whitelabel_id);
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
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
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

    $fields['campaign_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign ID'))
      ->setDescription(t('The campaign ID of DD Action Metrics entity.'))
      ->setSetting('target_type', 'node')
      ->setSetting('handler', 'default')
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
          'target_bundles' => array(
            'campaign' => 'campaign',
          ),
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor First Name'))
      ->setDescription(t('The first name of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor Last Name'))
      ->setDescription(t('The last name of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['email'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor Email'))
      ->setDescription(t('The email of the campaign visitor.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor Address'))
      ->setDescription(t('The street address of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 100,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor City'))
      ->setDescription(t('The city of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor State'))
      ->setDescription(t('The state of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 4,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['zip'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor Zip'))
      ->setDescription(t('The zipcode of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 20,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['county'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor County'))
      ->setDescription(t('The county of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['assembly_district'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Visitor Assembly District'))
      ->setDescription(t('The assembly district # of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 3,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['senate_district'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Visitor Senate District'))
      ->setDescription(t('The senate district # of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 3,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['session_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Session ID'))
      ->setDescription(t('The session ID of the campaign visitor.'))
      ->setSettings(array(
        'max_length' => 50,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Visitor User ID'))
      ->setDescription(t('The uid of the campaign visitor.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
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

    $fields['whitelabel_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Visitor Whitelabel ID'))
      ->setDescription(t('The whitelabel ID of the site.'))
      ->setSettings(array(
        'max_length' => 50,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Visitor Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Visitor Changed'))
      ->setDescription(t('The time that the entity was changed.'));

    return $fields;
  }

}
