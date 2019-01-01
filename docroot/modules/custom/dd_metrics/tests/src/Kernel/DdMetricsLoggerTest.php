<?php

namespace Drupal\Tests\dd_metrics\Kernel;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_metrics\Entity\DdActionMetrics;
use Drupal\dd_metrics\Entity\DdCampaignMetrics;
use Drupal\dd_metrics\Entity\DdCampaignVisitor;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Drupal\dd_metrics\Utility\DdCampaignVisitorHelper;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\token\Kernel\KernelTestBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Tests DdMetrics Logger.
 *
 * @coversDefaultClass \Drupal\dd_metrics\DdMetricsService
 * @group dd_metrics
 */

class DdMetricsLoggerTest extends KernelTestBase {
  /** @var \Drupal\dd_metrics\DdMetricsService */
  protected $logger;

  /** @var Session */
  protected $session;

  protected $visitorUser;
  protected $visitorUserData;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'system',
    'dd_base',
    'dd_metrics',
    'field',
    'node',
    'user',
    'address',
    'entity_reference_revisions',
    'paragraphs',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->enableModules(['dd_legislator']);
    $this->installEntitySchema('dd_campaign_metrics');
    $this->installEntitySchema('dd_action_metrics');
    $this->installEntitySchema('dd_campaign_visitor');
    $this->installEntitySchema('user');
    $this->installSchema('system', 'sequences');
    $this->installConfig(['field']);
    $this->installConfig(['address']);

    $this->logger = \Drupal::service('dd_metrics.logger');

    // No host is set from PHPUnit, set something so env detection doesn't warn.
    $_SERVER['HTTP_HOST'] = 'localhost.com';

    // Setup whitelabel basics.
    global $_dd_env;
    $_dd_env = new DdEnvironment();
    $_dd_env->setSiteType(DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL);
    $_dd_env->setWhiteLabelId('wltest');

    // Add field_address to user entity.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_address',
      'entity_type' => 'user',
      'type' => 'address',
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'user',
      'label' => 'Address',
    ]);

    $field->save();

    // Add field_first_name to user entity.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_first_name',
      'entity_type' => 'user',
      'type' => 'string',
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'user',
      'label' => 'First Name',
    ]);

    $field->save();

    // Add field_last_name to user entity.
    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_last_name',
      'entity_type' => 'user',
      'type' => 'string',
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => 'user',
      'label' => 'Last Name',
    ]);

    $field->save();

    // Start a Session.
    $this->session = \Drupal::service('session');
    $this->session->start();

    // Create a visitor user.

    $this->visitorUserData['first_name'] = 'Test';
    $this->visitorUserData['last_name'] = 'User';
    $this->visitorUserData['street_address'] = '1103 Laurel Lane';
    $this->visitorUserData['city'] = 'San Luis Obispo';
    $this->visitorUserData['state'] = 'CA';
    $this->visitorUserData['zip'] = '93401';
    $this->visitorUserData['country'] = 'US';

    // Create a sample user.
    $this->visitorUser = User::create(
      [
        'name' => 'testuser',
        'pass' => 'testuser',
        'status' => 1,
      ]
    );

    $address['country_code'] = $this->visitorUserData['country'];
    $address['address_line1'] = $this->visitorUserData['street_address'];
    $address['locality'] = $this->visitorUserData['city'];
    $address['administrative_area'] = $this->visitorUserData['state'];
    $address['postal_code'] = $this->visitorUserData['zip'];
    $this->visitorUser->set('field_address', $address);

    $this->visitorUser->set('field_first_name', $this->visitorUserData['first_name']);
    $this->visitorUser->set('field_last_name', $this->visitorUserData['last_name']);

    $this->visitorUser->save();
  }

  /**
   * Tests logCampaignMetric Exceptions.
   * @covers ::logCampaignMetric
   */
  public function testLogCampaignMetricExceptions() {

    $exception_caught = FALSE;
    // Test invalid Campaign ID, Metric Type.
    try {
      $this->logger->logCampaignMetric(NULL, NULL);
    }
    catch (\Exception $e) {
      $exception_caught = TRUE;
    }

    if (!$exception_caught) {
      $this->fail('Invalid Campaign ID InvalidArgumentException not thrown.');
    }

    $exception_caught = FALSE;

    // Test invalid Campaign ID.
    try {
      $this->logger->logCampaignMetric(NULL, DdCampaignMetricTypes::DD_METRICS_CAMPAIGN_FB_SHARES);
    }
    catch (\Exception $e) {
      $exception_caught = TRUE;
    }

    if (!$exception_caught) {
      $this->fail('Invalid Campaign ID InvalidArgumentException not thrown.');
    }

    $exception_caught = FALSE;

    // Test invalid Campaign Metric Type.
    try {
      $this->logger->logCampaignMetric(1, 'invalid_metric');
    }
    catch (\Exception $e) {
      $exception_caught = TRUE;
    }

    if (!$exception_caught) {
      $this->fail('Invalid Campaign Metric Type InvalidArgumentException not thrown.');
    }
  }

  /**
   * Tests logCampaignMetric.
   * @covers ::logCampaignMetric
   */
  public function testLogCampaignMetric() {
    // Add a metric test.
    $campaign_id = 1;
    $campaign_metric_type = DdCampaignMetricTypes::DD_METRICS_CAMPAIGN_VIEWS;
    $campaign_metric_id = $this->logger->logCampaignMetric($campaign_id, $campaign_metric_type);

    // Verify this is an add result, not update.
    $this->assertNotEmpty($campaign_metric_id, 'logCampaignMetric did not return valid campaign_metric_id');

    $campaign_metric = DdCampaignMetrics::load($campaign_metric_id);

    // Check fields.
    $this->assertEquals(1, $campaign_metric->getTotalCount(), 'logCampaignMetric totalCount');
    $this->assertEquals(DdBase::getWhiteLabelId(), $campaign_metric->getWhitelabelId(), 'logCampaignMetric whitelabel_id');
    $this->assertEquals($campaign_metric_type, $campaign_metric->getMetricType(), 'logCampaignMetric metric type');
    $this->assertEquals($campaign_id, $campaign_metric->getCampaignId(), 'logCampaignMetric metric type');

    // Test incrementing an existing metric for a campaign ID.
    $updated_campaign_metric_id = $this->logger->logCampaignMetric($campaign_id, $campaign_metric_type);

    // Verify campaign metric ID is the same.
    $this->assertEquals($updated_campaign_metric_id, $campaign_metric_id, 'logCampaignMetric did not return same campaign metric id');

    $campaign_metric = DdCampaignMetrics::load($campaign_metric_id);

    // Verify count incremented.
    $this->assertEquals(2, $campaign_metric->getTotalCount(), 'logCampaignMetric totalCount');
  }

  /**
   * Tests logActionMetric.
   * @covers ::logActionMetric
   */
  public function testLogActionMetric() {
    // Test data parameters.
    $campaign_id = 1;
    $campaign_action_id = 111;

    // @todo Add new Paragraphs entity in setup so we can test reference.
    $campaign_action_paragraphs_id = NULL;
    $target_pid = 9;
    $action_id = 'email_action';

    $campaign_metric_id = $this->logger->logActionMetric($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $target_pid, $this->visitorUser, FALSE);

    // Verify fields saved correctly.
    $action_metric = DdActionMetrics::load($campaign_metric_id);

    $this->assertEquals($campaign_id, $action_metric->getCampaignId(), 'Campaign ID mismatch');
    $this->assertEquals($action_id, $action_metric->getActionId(), 'Action ID mismatch');
    $this->assertEquals($target_pid, $action_metric->getTargetPid(), 'Target PID mismatch');
    $this->assertNotEmpty($action_metric->getCampaignVisitorId(), 'Visitor ID empty');

    // Load the visitor.
    $campaign_visitor = DdCampaignVisitor::load($action_metric->getCampaignVisitorId());

    $this->assertEquals($this->visitorUserData['first_name'], $campaign_visitor->getFirstName(), 'First Name mismatch');
    $this->assertEquals($this->visitorUserData['last_name'], $campaign_visitor->getLastName(), 'Last Name mismatch');
    $this->assertEquals($this->visitorUserData['street_address'], $campaign_visitor->getAddress(), 'Address mismatch');
    $this->assertEquals($this->visitorUserData['city'], $campaign_visitor->getCity(), 'City mismatch');
    $this->assertEquals($this->visitorUserData['state'], $campaign_visitor->getState(), 'State mismatch');
    $this->assertEquals($this->visitorUserData['zip'], $campaign_visitor->getZip(), 'Zip mismatch');
    // @todo enable when county is being populated.
    //$this->assertNotEmpty($campaign_visitor->getCounty(), 'County empty');
    $this->assertNotEmpty($campaign_visitor->getAssemblyDistrict(), 'Assembly District empty');
    $this->assertNotEmpty($campaign_visitor->getSenateDistrict(), 'Senate District empty');
    $this->assertNotEmpty($campaign_visitor->getSessionId(), 'Session ID empty');

    $this->assertEquals($this->visitorUser->id(), $campaign_visitor->getUserId(), 'User ID mismatch');

    // Perform a conversion.
    $action_metric_id = $this->logger->logActionConversion($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $target_pid, $this->visitorUser);
    $this->assertTrue($action_metric_id, 'No match on conversion parameters');

    $action_metric = DdActionMetrics::load($campaign_metric_id);
    $this->assertTrue($action_metric->isActionConverted(), 'No match on conversion parameters');

    // Test pre-set districts.
    $this->session->set('visitor_assembly_district', 999);
    $this->session->set('visitor_senate_district', 888);

    // Alter session, user so a new one is created.
    $this->session->setId('abc123');
    $this->visitorUser->set('name', 'testuser2');
    $this->visitorUser->set('field_first_name', 'Test2');
    $this->visitorUser = $this->visitorUser->createDuplicate();
    $this->visitorUser->save();

    $campaign_metric_id = $this->logger->logActionMetric($campaign_id, $action_id, $campaign_action_id, $campaign_action_paragraphs_id, $target_pid, $this->visitorUser, FALSE);

    $visitor_id = DdCampaignVisitorHelper::createCampaignVisitor($this->visitorUser, $campaign_id);

    $campaign_visitor = DdCampaignVisitor::load($visitor_id);
    $this->assertEquals(999, $campaign_visitor->getAssemblyDistrict());
    $this->assertEquals(888, $campaign_visitor->getSenateDistrict());
  }

  /**
   * Override to trick enableModules to run during setUp().
   *
   * {@inheritdoc}
   */
  protected function enableModules(array $modules) {
    parent::enableModules($modules);
  }
}
