<?php

namespace Drupal\dd_fax_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_metrics\Utility\DdCampaignMetricTypes;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dd_fax_service\Utility\CommonHelper;
use Drupal\dd_base\DdBase;

/**
 * Class Fax Service Controller.
 *
 * @package Drupal\dd_fax_service\Controller
 */
class DdFaxServiceController extends ControllerBase {
  private $args;

  /**
   * A function to get links to fax legislators.
   *
   * @return renderable array
   *   renderable array for links 
   */
  public function getLinks($state, $zip, $city, $street, $arg) {
    parse_str($arg, $args);
    $legs = dd_fax_service_get_fax_links($state, $zip, urldecode($city),
                                         urldecode($street),
                                         urldecode($args['first']),
                                         urldecode($args['last']),
                                         urldecode($args['contact']),
                                         urldecode($args['body']),
                                         urldecode($args['subject']));
    return array(
      '#theme' => 'dd-fax-service-links',
      '#links' => $legs['legislators']);
  }

  /**
   * A function to get links to fax legislators.
   *
   * @return array
   *   renderable array for form 
   */
  public function buildFaxForm($fax_num, $arg) {
    parse_str($arg, $args);
    $this->setArgs($args);
    $sender = $this->getSender();
    $build_args = array(
      'sender' => $sender,
      'to' => $this->args['to'],
      'fax_num' => $fax_num,
      'subject' => $this->args['subject'],
      'body' => $this->args['body'],
      'back_link' => \Drupal::request()->server->get('HTTP_REFERER'),
    );

    if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL && isset($this->args['campaign_id'])) {
      // Log Action Metric.
      $campaign_action_id = isset($this->args['campaign_action_id']) ? $this->args['campaign_action_id'] : NULL;
      $campaign_action_paragraphs_id = isset($this->args['campaign_action_paragraphs_id']) ? $this->args['campaign_action_paragraphs_id'] : NULL;
      \Drupal::service('dd_metrics.logger')
        ->logActionMetric($this->args['campaign_id'], $this->args['action_id'], $campaign_action_id, $campaign_action_paragraphs_id, $this->args['target_pid']);

      $build_args['campaign_id'] = $this->args['campaign_id'];
      $build_args['action_id'] = $this->args['action_id'];
      $build_args['campaign_action_id'] = $campaign_action_id;
      $build_args['campaign_action_paragraphs_id'] = $campaign_action_paragraphs_id;
      $build_args['target_pid'] = $this->args['target_pid'];
    }

    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_fax_service\Form\FaxPreviewForm', $build_args);

    $fax_data = CommonHelper::buildFaxData($fax_num, $this->args['to'],
                             $this->args['subject'], $this->args['body'],
                             $sender);

    return array(
      '#theme' => 'dd-fax-service-preview-form',
      '#form' => $form,
      '#fax' => $fax_data,
    );
  }

  private function setArgs($args) {
    $this->args = array();
    foreach ($args as $key => $val) {
      $this->args[$key] = urldecode($val);
    }
  }

  private function getSender() {
    return array(
      'first' => $this->args['first'],
      'last' => $this->args['last'],
      'contact' => isset($this->args['contact']) ? $this->args['contact']: '',
      'state' => $this->args['state'],
      'zip' => $this->args['zip'],
      'city' => $this->args['city'],
      'street' => $this->args['street'],
    );
  }
}
