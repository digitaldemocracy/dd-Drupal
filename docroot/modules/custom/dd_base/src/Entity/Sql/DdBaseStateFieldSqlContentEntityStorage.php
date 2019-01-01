<?php

namespace Drupal\dd_base\Entity\Sql;

use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\dd_base\DdBase;

/**
 * Class DdBaseStateFieldSqlContentEntityStorage
 * @package Drupal\dd_base\Entity\Sql
 */
class DdBaseStateFieldSqlContentEntityStorage extends DdBaseSqlContentEntityStorage {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    if (DdBaseStateField::doesTableUseStateField($this->getBaseTable())) {
      $query->condition('base.state', DdBase::getCurrentState());
    }
    return $query;
  }
}
