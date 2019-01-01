<?php

namespace Drupal\dd_metrics\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the DD Campaign Metrics entity.
 *
 * @ingroup dd_metrics
 *
 * @ContentEntityType(
 *   id = "dd_campaign_metrics",
 *   label = @Translation("DD Campaign Metrics"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_metrics\DdCampaignMetricsListBuilder",
 *     "views_data" = "Drupal\dd_metrics\Entity\DdCampaignMetricsViewsData",
 *     "storage_schema" = "Drupal\dd_metrics\DdMetricsStorageSchema",
 *
 *   },
 *   base_table = "dd_campaign_metrics",
 *   admin_permission = "administer dd campaign metrics entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "created",
 *   },
 * )
 */
class DdCampaignMetrics extends ContentEntityBase implements DdCampaignMetricsInterface {

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
  public function getMetricType() {
    return $this->get('metric_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMetricType($timestamp) {
    $this->set('metric_type', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getTotalCount() {
    return $this->get('total_count')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTotalCount($timestamp) {
    $this->set('total_count', $timestamp);
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

    $fields['metric_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Metric Type'))
      ->setDescription(t('The Metric Type ID of this campaign metric.'))
      ->setSettings(array(
        'max_length' => 50,
      ))
      ->setDefaultValue('')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['total_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Total Count'))
      ->setDescription(t('The total count for this metric type.'))
      ->setDefaultValue(0)
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
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
