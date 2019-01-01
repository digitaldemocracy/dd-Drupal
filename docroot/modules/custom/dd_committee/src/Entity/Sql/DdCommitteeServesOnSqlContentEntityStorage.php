<?php

namespace Drupal\dd_committee\Entity\Sql;

use Drupal\dd_base\DdBase;
use Drupal\dd_person\Entity\Sql\DdPersonFieldsSqlContentEntityStorage;

/**
 * Class DdCommitteeServesOnSqlContentEntityStorage
 * @package Drupal\dd_committee\Entity\Sql
 */
class DdCommitteeServesOnSqlContentEntityStorage extends DdPersonFieldsSqlContentEntityStorage {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    $query->leftJoin('House', 'h', 'base.house = h.name');
    $query->addField('h', 'type', 'house_type');
    $query->condition('base.state', DdBase::getCurrentState());

    return $query;
  }
}
