<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Entity\EntityListBuilder;
use Drupal\dd_base\DdBase;

/**
 * Defines a class to build a listing of entities filtered by state.
 *
 * @ingroup dd_base
 */
class DdBaseStateFieldEntityListBuilder extends EntityListBuilder {
  /**
   * Loads entity IDs using a pager sorted by the entity id.
   *
   * Add conditional state filtering if necessary.
   * @return array
   *   An array of entity IDs.
   */
  protected function getEntityIds() {
    $query = $this->getStorage()->getQuery()
      ->sort($this->entityType->getKey('id'));

    if (DdBaseStateField::doesEntityUseStateField($this->entityTypeId)) {
      $query->condition('state', DdBase::getCurrentState());
    }

    // Only add the pager if a limit is specified.
    if ($this->limit) {
      $query->pager($this->limit);
    }
    return $query->execute();
  }
}
