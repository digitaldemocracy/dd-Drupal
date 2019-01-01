<?php

namespace Drupal\dd_metrics\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dd_metrics\DdMetricsServiceInterface;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dd_metrics\DdMetricsService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DdMetricsController.
 *
 * @package Drupal\dd_metrics\Controller
 */
class DdMetricsController extends ControllerBase {

  /**
   * Drupal\dd_metrics\DdMetricsService definition.
   *
   * @var \Drupal\dd_metrics\DdMetricsService
   */
  protected $ddMetricsLogger;

  /**
   * {@inheritdoc}
   */
  public function __construct(DdMetricsService $dd_metrics_logger) {
    $this->ddMetricsLogger = $dd_metrics_logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dd_metrics.logger')
    );
  }

  /**
   * Log Campaign Metric Callback.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public static function logCampaignMetricCallback(array $form, FormStateInterface $form_state) {
    $campaign_id = (int) $form_state->get('campaign_id');
    $metric_type = $form_state->get('metric_type');

    \Drupal::service('dd_metrics.logger')->logCampaignMetric($campaign_id, $metric_type);
  }

  /**
   * Log Action Metric Callback.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public static function logActionMetricCallback(array $form, FormStateInterface $form_state) {
    $campaign_id = (int) $form_state->get('campaign_id');
    $campaign_action_id = (int) $form_state->get('campaign_action_id');
    $campaign_action_paragraphs_id = (int) $form_state->get('campaign_action_paragraphs_id');
    $action_id = (int) $form_state->get('action_id');
    $visitor_user = $form_state->get('visitor_user');
    $target_id = (int) $form_state->get('target_id');
    $action_conversion = (bool) $form_state->get('action_conversion');

    \Drupal::service('dd_metrics.logger')->logActionMetric($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $visitor_user, $target_id, $action_conversion);
  }

  /**
   * Log Action Conversion Callback.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public static function logActionConversionCallback(array $form, FormStateInterface $form_state) {
    $campaign_id = (int) $form_state->get('campaign_id');
    $campaign_action_id = (int) $form_state->get('campaign_action_id');
    $campaign_action_paragraphs_id = (int) $form_state->get('campaign_action_paragraphs_id');
    $action_id = (int) $form_state->get('action_id');
    $visitor_user = $form_state->get('visitor_user');
    $target_id = (int) $form_state->get('target_id');

    \Drupal::service('dd_metrics.logger')->logActionConversion($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $visitor_user, $target_id);
  }


  /**
   * Log Campaign Metric.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $metric_type
   *   DdCampaignMetricTypes constant
   *
   * @return JsonResponse
   *   Array with 'success', 'message', 'entity_id' of created metric.
   */
  public static function logCampaignMetric($campaign_id, $metric_type) {
    try {
      $result = \Drupal::service('dd_metrics.logger')
        ->logCampaignMetric($campaign_id, $metric_type);
    }
    catch (\Exception $e) {
      return new JsonResponse(['success' => FALSE, 'message' => $e->getMessage()], 500);
    }
    return new JsonResponse(['entity_id' => $result, 'success' => TRUE]);
  }

  /**
   * Log Action Metric.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $action_id
   *   Action ID (paragraph machine name)
   * @param int $campaign_action_id
   *   Campaign Action ID
   * @param int $campaign_action_paragraphs_id
   *   Campaign Action Paragraphs ID
   * @param int $target_pid
   *   Target Legislator PID
   * @param bool $action_conversion
   *   TRUE if action has converted, FALSE otherwise (default).
   *
   * @return JsonResponse
   *   Array with 'success', 'message', 'entity_id' of created metric.
   */
  public static function logActionMetric($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL, $action_conversion = FALSE) {
    try {
      $result = \Drupal::service('dd_metrics.logger')
        ->logActionMetric($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $target_pid, NULL, $action_conversion);
    }
    catch (\Exception $e) {
      return new JsonResponse(['success' => FALSE, 'message' => $e->getMessage()], 500);
    }
    return new JsonResponse(['entity_id' => $result, 'success' => TRUE]);
  }


  /**
   * Log Action Metric Conversion.
   *
   * @param int $campaign_id
   *   Campaign ID
   * @param string $action_id
   *   Action ID (paragraph machine name)
   * @param int $campaign_action_id
   *   Campaign Action ID
   * @param int $campaign_action_paragraphs_id
   *   Campaign Action Paragraphs ID
   * @param int $target_pid
   *   Target Legislator PID
   *
   * @return JsonResponse
   *   Array with 'success', 'message', 'entity_id' of created metric.
   */
  public static function logActionMetricConversion($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL) {
    try {
      $result = \Drupal::service('dd_metrics.logger')
        ->logActionMetricConversion($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $target_pid);
    }
    catch (\Exception $e) {
      return new JsonResponse(['success' => FALSE, 'message' => $e->getMessage()], 500);
    }
    return new JsonResponse(['entity_id' => $result, 'success' => TRUE]);
  }
}
