<?php

namespace Drupal\dd_legislator\Entity\Sql;

use Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage;

/**
 * Class DdGovernorSqlContentEntityStorage
 * @package Drupal\dd_legislator\Entity\Sql
 */
class DdGovernorSqlContentEntityStorage extends DdBaseStateFieldSqlContentEntityStorage {
  /**
   * Do buildQuery override to add table join.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = parent::buildQuery($ids, $revision_id);
    $query->leftJoin('Person', 'p', 'base.pid = p.pid');

    $query->addField('p', 'first');
    $query->addField('p', 'middle');
    $query->addField('p', 'last');
    $query->addField('p', 'image');
    return $query;
  }
}
