<?php

namespace Drupal\dd_email_ca_legislator;

use Drupal\Core\Url;
use Drupal\dd_email_legislator\DdEmailLegislatorInterface;

/**
 * Class DdEmailLegislatorCA
 * @package Drupal\dd_email_ca_legislator
 */
class DdEmailLegislatorCA implements DdEmailLegislatorInterface {
  /**
   * {@inheritdoc}
   */
  public static function getEmailUrlForState($dd_user, $legislator, $term, $body, $subject, $campaign_id = '') {
    $query_args = $dd_user->toEmailFormArray();
    $query_args['message'] = html_entity_decode($body);
    $query_args['campaign_id'] = $campaign_id;

    $url = Url::fromRoute(
      'dd_email_ca_legislator.contact_form',
      [
        'house' => $term->getHouse(),
        'district' => $term->getDistrict(),
        'pid' => $legislator->id(),
      ],
      ['query' => $query_args]);

    return ['url' => $url, 'target' => '_blank'];
  }
}
