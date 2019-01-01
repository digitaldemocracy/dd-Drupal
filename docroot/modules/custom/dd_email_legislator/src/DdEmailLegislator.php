<?php

namespace Drupal\dd_email_legislator;

use Drupal\Core\Url;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdUser;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_legislator\Entity\DdTerm;

class DdEmailLegislator {
  /**
   * Get Email URL.
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
   * @param string $state
   *   State, or current state if NULL.
   *
   * @return array
   *   Array with ['url' => \Drupal\Core\Url object, 'target' => target]
   */
  public static function getEmailUrl($dd_user, $legislator, $term, $body, $subject, $campaign_id = '', $state = NULL) {
    if (empty($state)) {
      $state = DdBase::getCurrentState();
    }

    $class = '\Drupal\dd_email_' . strtolower($state) . '_legislator\DdEmailLegislator' . $state;
    return $class::getEmailUrlForState($dd_user, $legislator, $term, $body, $subject, $campaign_id);
  }
}
