<?php

namespace Drupal\dd_committee\Entity\Sql;

use Drupal\Core\Database\Query\Select;
use Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage;
use Drupal\dd_base\DdBase;

/**
 * Class DdConsultantServesOnSqlContentEntityStorage
 * @package Drupal\dd_committee\Entity\Sql
 */
class DdConsultantServesOnSqlContentEntityStorage extends DdBaseStateFieldSqlContentEntityStorage  {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);

    return $query;
  }
}
