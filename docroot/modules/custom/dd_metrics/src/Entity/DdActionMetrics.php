<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD Action Metrics entity.
 *
 * @ingroup dd_metrics
 *
 * @ContentEntityType(
 *   id = "dd_action_metrics",
 *   label = @Translation("DD Action Metrics"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_metrics\DdActionMetricsListBuilder",
 *     "views_data" = "Drupal\dd_metrics\Entity\DdActionMetricsViewsData",
 *     "storage_schema" = "Drupal\dd_metrics\DdMetricsStorageSchema",
 *
 *   },
 *   base_table = "dd_action_metrics",
 *   admin_permission = "administer dd action metrics entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "created",
 *     "uid" = "visitor_uid",
 *   },
 * )
 */
class DdActionMetrics extends ContentEntityBase implements DdActionMetricsInterface {

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
  public function getCampaignActionId() {
    return $this->get('campaign_action_id')->getValue() ? $this->get('campaign_action_id')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setCampaignActionId($id) {
    $this->set('campaign_action_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCampaignActionParagraphsId() {
    return $this->get('campaign_action_paragraphs_id')->getValue() ? $this->get('campaign_action_paragraphs_id')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setCampaignActionParagraphsId($id) {
    $this->set('campaign_action_paragraphs_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getActionId() {
    return $this->get('action_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setActionId($action_id) {
    $this->set('action_id', $action_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCampaignVisitorId() {
    return $this->get('campaign_visitor_id')->getValue() ? $this->get('campaign_visitor_id')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setCampaignVisitorId($visitor_id) {
    $this->set('campaign_visitor_id', $visitor_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isActionConverted() {
    return $this->get('action_conversion')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setActionConversion($action_conversion) {
    $this->set('action_conversion', $action_conversion);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTargetPid() {
    return $this->get('target_pid')->getValue() ? $this->get('target_pid')->getValue()[0]['target_id'] : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setTargetPid($target_pid) {
    $this->set('target_pid', $target_pid);
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

    $fields['campaign_action_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign Action ID'))
      ->setDescription(t('The campaign action ID of DD Action Metrics entity.'))
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
            'campaign_action' => 'campaign_action',
          ),
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['campaign_action_paragraphs_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign Action Paragraphs ID'))
      ->setDescription(t('The campaign action paragraphs ID of DD Action Metrics entity.'))
      ->setSetting('target_type', 'paragraph')
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

    $fields['action_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Action ID'))
      ->setDescription(t('The machine ID of the campaign action.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['campaign_visitor_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Campaign Visitor ID'))
      ->setDescription(t('The campaign visitor ID of DD Action Metrics entity.'))
      ->setSetting('target_type', 'dd_campaign_visitor')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['action_conversion'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Action Conversion'))
      ->setDescription(t('A boolean indicating whether the action converted.'))
      ->setDefaultValue(TRUE);

    $fields['target_pid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Target Person ID'))
      ->setDescription(t('The target legislator PID of DD Action Metrics entity.'))
      ->setSetting('target_type', 'dd_person')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['whitelabel_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Whitelabel ID'))
      ->setDescription(t('The whitelabel ID of the site.'))
      ->setSettings(array(
        'max_length' => 50,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was changed.'));

    return $fields;
  }

}
