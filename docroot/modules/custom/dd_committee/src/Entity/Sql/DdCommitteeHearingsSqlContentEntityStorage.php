<?php

namespace Drupal\dd_committee\Entity\Sql;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;

/**
 * Class DdCommitteeSqlContentEntityStorage
 * @package Drupal\dd_committee\Entity\Sql
 */
class DdCommitteeHearingsSqlContentEntityStorage extends DdBaseSqlContentEntityStorage  {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    $query->join('Committee', 'c', 'base.cid = c.cid');
    $query->condition('c.state', DdBase::getCurrentState());

    return $query;
  }
}
