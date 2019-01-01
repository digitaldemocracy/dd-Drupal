<?php

namespace Drupal\dd_email_legislator;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdUser;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_legislator\Entity\DdTerm;

interface DdEmailLegislatorInterface {
  /**
   * Get Email URL For State.
   *
   * @param DdUser $dd_user
   *   DdUser object
   * @param DdLegislator $legislator
   *   Legislator Entity
   * @param DdTerm $term
   *   Term Entity
   * @param string $body
   *   Email Body
   * @param string $subject
   *   Email Subject
   * @param string $campaign_id
   *   Optional Campaign ID
   *
   * @return array
   *   Array with ['url' => \Drupal\Core\Url object, 'target' => target]
   */
  public static function getEmailUrlForState($dd_user, $legislator, $term, $body, $subject, $campaign_id = '');
}
