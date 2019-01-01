<?php

namespace Drupal\dd_person\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\dd_person\Entity\DdPerson;

/**
 * Provides specific matches for the dd person entity type.
 *
 * @EntityReferenceSelection(
 *   id = "default:dd_person",
 *   label = @Translation("DD Person selection"),
 *   entity_types = {"dd_person"},
 *   group = "default",
 *   weight = 1
 * )
 */
class DdPersonSelection extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $options = [];
    $persons = DdPerson::getPersonMatches($match, $limit, '', FALSE);
    if ($persons) {
      foreach ($persons as $person) {
        $options['dd_person'][$person->pid] = '<span class="dd-search-text">' . $person->fullname . '</span> <span class="dd-search-text--person-type">' . $person->type_label . '</span>';
      }
    }
    return $options;
  }
}
