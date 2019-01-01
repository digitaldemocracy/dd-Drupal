<?php

namespace Drupal\dd_person\Entity\Sql;

use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;

/**
 * Class DdPersonSqlContentEntityStorage
 * @package Drupal\dd_person\Entity\Sql
 */
class DdPersonFieldsSqlContentEntityStorage extends DdBaseSqlContentEntityStorage {
  /**
   * Do buildQuery override to add tables.
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
    // @todo Is Distinct necessary?
    $query->distinct(TRUE);
    return $query;
  }
}
