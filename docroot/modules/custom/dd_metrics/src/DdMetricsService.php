<?php

namespace Drupal\dd_metrics;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_metrics\Entity\DdActionMetrics;
use Drupal\dd_metrics\Entity\DdCampaignMetrics;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Drupal\dd_metrics\Utility\DdCampaignVisitorHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class DdMetricsService.
 *
 * @package Drupal\dd_metrics
 */
class DdMetricsService implements DdMetricsServiceInterface {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * Drupal\Core\Database\Connection definition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Symfony\Component\EventDispatcher\EventDispatcherInterface definition.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * Symfony\Component\HttpFoundation\Session\SessionInterface definition.
   *
   * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
   */
  protected $session;

  /**
   * Constructor.
   *
   * @param AccountProxy $current_user
   *   Current User
   *
   * @param Connection $connection
   *   Database Connection
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   Entity Type Manager
   *
   * @param EventDispatcherInterface $event_dispatcher
   *   Event Dispatcher
   *
   * @param SessionInterface $session
   *   Session
   */
  public function __construct(AccountProxy $current_user, Connection $connection, EntityTypeManagerInterface $entity_type_manager, EventDispatcherInterface $event_dispatcher, SessionInterface $session) {
    $this->currentUser = $current_user;
    $this->connection = $connection;
    $this->entityTypeManager = $entity_type_manager;
    $this->eventDispatcher = $event_dispatcher;
    $this->session = $session;
  }

  /**
   * {@inheritdoc}
   */
  public function logCampaignMetric($campaign_id, $metric_type) {
    // Validate variables.

    if (DdBase::getSiteType() != DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      throw new \BadMethodCallException('Not a Whitelabel Site');
    }

    if (empty(DdBase::getWhiteLabelId())) {
      throw new \InvalidArgumentException('Missing whitelabel_id');
    }

    if (!DdCampaignMetricTypes::isValidValue($metric_type)) {
      throw new \InvalidArgumentException('Metric type is invalid');
    }

    if (empty($campaign_id)) {
      throw new \InvalidArgumentException('Missing campaign_id');
    }

    if (!is_numeric($campaign_id)) {
      throw new \InvalidArgumentException('Invalid campaign_id');
    }

    $whitelabel_id = DdBase::getWhiteLabelId();
    $now = time();

    // Check if row exists.
    $query = \Drupal::entityQuery('dd_campaign_metrics')
      ->condition('campaign_id', $campaign_id)
      ->condition('metric_type', $metric_type)
      ->condition('whitelabel_id', $whitelabel_id);

    $nids = $query->execute();

    if ($nids) {
      // Metric exists, increment count.
      $entity = DdCampaignMetrics::load(reset($nids));
      $entity->setTotalCount($entity->getTotalCount() + 1);
    }
    else {
      // Metric doesn't exist, insert.
      $data = [
        'campaign_id' => $campaign_id,
        'whitelabel_id' => $whitelabel_id,
        'created' => $now,
        'changed' => $now,
        'metric_type' => $metric_type,
        'total_count' => 1,
      ];
      $entity = DdCampaignMetrics::create($data);
    }
    $entity->save();

    // Dispatch a log_campaign_metric event.
    $event = new DdMetricsEvent();
    $event->setCampaignMetric($entity);

    $this->eventDispatcher->dispatch('dd_metrics.log_campaign_metric', $event);

    return $entity->id();
  }

  /**
   * {@inheritdoc}
   */
  public function logActionMetric($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL, $visitor_user = NULL, $action_conversion = FALSE) {
    $address = NULL;
    $city = NULL;
    $state = NULL;
    $zip = NULL;
    $county = NULL;
    $visitor_assembly_district = NULL;
    $visitor_senate_district = NULL;


    // Use session visitor_user if NULL.
    if (empty($visitor_user)) {
      $visitor_user = DdActionCenterCampaignHelper::getVisitor();
    }
    // Validate variables.

    if (DdBase::getSiteType() != DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      throw new \BadMethodCallException('Not a Whitelabel Site');
    }

    if (empty(DdBase::getWhiteLabelId())) {
      throw new \InvalidArgumentException('Missing whitelabel_id');
    }

    if (empty($campaign_id)) {
      throw new \InvalidArgumentException('Missing campaign_id');
    }

    if (!is_numeric($campaign_id)) {
      throw new \InvalidArgumentException('Invalid campaign_id');
    }

    if (empty($action_id)) {
      throw new \InvalidArgumentException('Missing action_id');
    }

    $now = time();
    $visitor_id = DdCampaignVisitorHelper::createCampaignVisitor($visitor_user, $campaign_id);

    $data = [
      'campaign_id' => $campaign_id,
      'action_id' => $action_id,
      'campaign_visitor_id' => $visitor_id,
      'action_conversion' => $action_conversion,
      'whitelabel_id' => DdBase::getWhiteLabelId(),
      'created' => $now,
      'changed' => $now,
    ];

    if (!empty($campaign_action_id)) {
      $data['campaign_action_id'] = $campaign_action_id;
    }
    if (!empty($campaign_action_paragraphs_id)) {
      $data['campaign_action_paragraphs_id'] = $campaign_action_paragraphs_id;
    }
    if (!empty($target_pid)) {
      $data['target_pid'] = $target_pid;
    }

    $entity = DdActionMetrics::create($data);
    $entity->save();

    // Dispatch a log_action_metric event.
    $event = new DdMetricsEvent();
    $event->setActionMetric($entity);

    $this->eventDispatcher->dispatch('dd_metrics.log_action_metric', $event);

    return $entity->id();
  }

  /**
   * {@inheritdoc}
   */
  public function logActionConversion($campaign_id, $action_id, $campaign_action_id = NULL, $campaign_action_paragraphs_id = NULL, $target_pid = NULL, $visitor_user = NULL) {
    // Use session visitor_user if NULL.
    if (empty($visitor_user)) {
      $visitor_user = DdActionCenterCampaignHelper::getVisitor();
    }
    // Validate variables.

    if (DdBase::getSiteType() != DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      throw new \BadMethodCallException('Not a Whitelabel Site');
    }

    if (empty(DdBase::getWhiteLabelId())) {
      throw new \InvalidArgumentException('Missing whitelabel_id');
    }

    if (empty($campaign_id)) {
      throw new \InvalidArgumentException('Missing campaign_id');
    }

    if (!is_numeric($campaign_id)) {
      throw new \InvalidArgumentException('Invalid campaign_id');
    }

    if (empty($visitor_user)) {
      throw new \InvalidArgumentException('Missing visitor_user');
    }

    if (empty($action_id)) {
      throw new \InvalidArgumentException('Missing action_id');
    }

    $now = time();

    $visitor_id = DdCampaignVisitorHelper::checkIfVisitorExists($visitor_user, $campaign_id);

    if (!$visitor_id) {
      throw new \InvalidArgumentException('Could not find visitor id');
    }

    // Check if row exists.
    $query = \Drupal::entityQuery('dd_action_metrics')
      ->condition('campaign_id', $campaign_id)
      ->condition('action_id', $action_id)
      ->condition('action_conversion', 0)
      ->condition('whitelabel_id', DdBase::getWhiteLabelId());

    if (!empty($campaign_action_id)) {
      $query->condition('campaign_action_id', $campaign_action_id);
    }
    else {
      $query->notExists('campaign_action_id');
    }

    if (!empty($campaign_action_paragraphs_id)) {
      $query->condition('campaign_action_paragraphs_id', $campaign_action_paragraphs_id);
    }
    if (!empty($target_pid)) {
      $query->condition('target_pid', $target_pid);
    }

    $nids = $query->execute();

    // Update matching action metric.
    if ($nids) {
      $entity = DdActionMetrics::load(reset($nids));
      $entity->setActionConversion(TRUE)
        ->setChangedTime($now)
        ->save();

      // Dispatch a log_action_conversion event.
      $event = new DdMetricsEvent();
      $event->setActionMetric($entity);

      $this->eventDispatcher->dispatch('dd_metrics.log_action_conversion', $event);

      return $entity->id();
    }

    return FALSE;
  }
}
