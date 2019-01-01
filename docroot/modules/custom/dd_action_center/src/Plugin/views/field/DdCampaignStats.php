<?php
/**
 * @file
 * Definition of Drupal\dd_action_center\Plugin\views\field\DdPhoneLog
 */

namespace Drupal\dd_action_center\Plugin\views\field;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_metrics\Entity\DdCampaignMetrics;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\Render\ViewsRenderPipelineMarkup;
use Drupal\views\ResultRow;

/**
 * Field handler to provide Campaign Stats.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_campaign_stats")
 */
class DdCampaignStats extends FieldPluginBase {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['campaign_id'] = array('default' => '');
    $options['campaign_action_id'] = array('default' => '');
    $options['target_pid'] = array('default' => '');
    $options['district'] = array('default' => '');
    $options['house'] = array('default' => '');
    $options['stat_display'] = array('default' => 'actions_taken');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['campaign_id'] = [
      '#title' => $this->t('Campaign ID'),
      '#description' => $this->t('Campaign ID, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['campaign_id'],
    ];
    $form['campaign_action_id'] = [
      '#title' => $this->t('Campaign Action ID'),
      '#description' => $this->t('Campaign Action ID, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['campaign_action_id'],
    ];
    $form['target_pid'] = [
      '#title' => $this->t('Target PID'),
      '#description' => $this->t('Target Person ID, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['target_pid'],
    ];
    $form['district'] = [
      '#title' => $this->t('District'),
      '#description' => $this->t('District, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['district'],
    ];
    $form['house'] = [
      '#title' => $this->t('House'),
      '#description' => $this->t('House (Assembly/Senate), supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['house'],
    ];

    $form['stat_display'] = [
      '#title' => $this->t('Stat To Display'),
      '#description' => $this->t('Stat to display'),
      '#type' => 'radios',
      '#default_value' => $this->options['stat_display'],
      '#options' => [
        'actions_taken' => $this->t('# of Actions Taken'),
        'conversions' => $this->t('# of Conversions'),
        'visitors_from_district' => $this->t('# of Visitors From District'),
        'visitors' => $this->t('# of Visitors'),
      ],
    ];
    $form['replacement_patterns'] = $form['alter']['help'];
    unset($form['replacement_patterns']['#states']);
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $stat = '';
    $campaign_id = 0;
    $campaign_action_id = 0;
    $target_pid = 0;
    $house = '';
    $district = 0;

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['campaign_id'])) {
      $campaign_id = $this->tokenizeValue($this->options['campaign_id'], $values->index);
    }
    if (!empty($this->options['campaign_action_id'])) {
      $campaign_action_id = $this->tokenizeValue($this->options['campaign_action_id'], $values->index);
    }
    if (!empty($this->options['target_pid'])) {
      $target_pid = $this->tokenizeValue($this->options['target_pid'], $values->index);
    }
    if (!empty($this->options['house'])) {
      $house = $this->tokenizeValue($this->options['house'], $values->index);
    }
    if (!empty($this->options['district'])) {
      $district = $this->tokenizeValue($this->options['district'], $values->index);
    }

    switch ($this->options['stat_display']) {
      case 'actions_taken':
        $stat = self::getActionsTaken($campaign_id, $campaign_action_id, $target_pid);
        break;

      case 'conversions':
        $stat = self::getActionsTaken($campaign_id, $campaign_action_id, $target_pid, TRUE);
        break;

      case 'visitors_from_district':
        $stat = self::getVisitorsFromDistrict($campaign_id, $house, $district);
        break;

      case 'visitors':
        $stat = self::getVisitors($campaign_id);
        break;
    }

    // Return the text, so the code never thinks the value is empty.
    return ViewsRenderPipelineMarkup::create(Xss::filterAdmin($stat));

  }

  /**
   * Get Actions Taken count.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param int $campaign_action_id
   *   Campaign Action ID
   * @param int $target_pid
   *   Target PID
   * @param bool $conversions_only
   *   Only show conversions if TRUE
   *
   * @return int
   *   Count
   */
  protected static function getActionsTaken($campaign_id, $campaign_action_id, $target_pid, $conversions_only = FALSE) {
    $query = \Drupal::entityQuery('dd_action_metrics')
      ->condition('campaign_id', $campaign_id);

    if ($campaign_action_id) {
      $query->condition('campaign_action_id', $campaign_action_id);
    }
    if ($target_pid) {
      $query->condition('target_pid', $target_pid);
    }

    if ($conversions_only) {
      $query->condition('action_conversion', TRUE);
    }

    $stat = $query->count()->execute();
    return $stat;
  }

  /**
   * Get # of Visitors From a District.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $house
   *   House (Assembly/Senate)
   * @param int $district
   *   District
   *
   * @return int
   *   # of Visitors
   */
  protected static function getVisitorsFromDistrict($campaign_id, $house, $district) {
    $query = \Drupal::entityQuery('dd_campaign_visitor')
      ->condition('campaign_id', $campaign_id);

    if (strtolower($house) == 'assembly') {
      $query->condition('assembly_district', $district);
    }
    elseif (strtolower($house) == 'senate') {
      $query->condition('senate_district', $district);
    }

    $stat = $query->count()->execute();
    return $stat;
  }

  /**
   * Get # of Visitors From a District.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $house
   *   House (Assembly/Senate)
   * @param int $district
   *   District
   *
   * @return int
   *   # of Visitors
   */
  protected static function getVisitors($campaign_id) {
    $query = \Drupal::entityQuery('dd_campaign_metrics')
      ->condition('metric_type', 'campaign_views')
      ->condition('campaign_id', $campaign_id);
    $nids = $query->execute();
    if ($nids) {
      $metric = DdCampaignMetrics::load(reset($nids));
      return $metric->getTotalCount();
    }
    return 0;
  }
}
