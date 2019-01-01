<?php

namespace Drupal\dd_email_ny_legislator;

use Drupal\Core\Url;
use Drupal\dd_email_legislator\DdEmailLegislatorInterface;

/**
 * Class DdEmailLegislatorNY
 * @package Drupal\dd_email_ny_legislator
 */
class DdEmailLegislatorNY implements DdEmailLegislatorInterface {
  /**
   * {@inheritdoc}
   */
  final public static function getEmailUrlForState($dd_user, $legislator, $term, $body, $subject, $campaign_id = '') {
    $query_args = $dd_user->toEmailFormArray();
    $body = html_entity_decode($body);
    $query_args['message'] = $body;
    $signature = <<<HDOC
{$dd_user->getFirstName()} {$dd_user->getLastName()}
{$dd_user->getEmail()}
{$dd_user->getStreet()}
{$dd_user->getCity()}, {$dd_user->getState()} {$dd_user->getZip()}
HDOC;

    $body = $body . "\n\n\n" . $signature;
    $url = Url::fromUri('mailto:' . $legislator->getEmail(), ['query' => ['body' => $body, 'subject' => $subject]]);

    return ['url' => $url, 'target' => '_self'];
  }
}
