<?php

namespace Drupal\dd_hearing\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\dd_base\DdBase;
use Drupal\dd_hearing\Entity\DdHearing;

/**
 * Entity Reference Selection plugin for Dd Hearing.
 *
 * @see plugin_api
 *
 * @EntityReferenceSelection(
 *   id = "default:dd_hearing",
 *   label = @Translation("DD Hearing Selection"),
 *   group = "default",
 *   entity_types = {"dd_hearing"},
 *   weight = 1,
 * )
 */
class DdHearingSelection extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $filtered = [];

    // Allows for a Hearing HID to be entered, or a search phrase.
    if (preg_match('/^[0-9]+$/', $match)) {
      $hearing = DdHearing::load($match);
      $filtered['dd_hearing'][$hearing->id()] = $hearing->label();
    }
    else {
      $matches = DdHearing::getCommitteeHearingMatches($match, $limit);
      if ($matches) {
        foreach ($matches as $match) {
          $hearing = DdHearing::load($match);
          $filtered['dd_hearing'][$hearing->id()] = $hearing->label();
        }
      }
    }

    return $filtered;
  }
}
