<?php

namespace Drupal\dd_hearing\Entity\Sql;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;

/**
 * Class DdHearingAgendaSqlContentEntityStorage
 * @package Drupal\dd_hearing\Entity\Sql
 */
class DdHearingAgendaSqlContentEntityStorage extends DdBaseSqlContentEntityStorage  {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    $query->join('Hearing', 'h', 'base.hid = h.hid');
    $query->addField('h', 'state');
    $query->condition('h.state', DdBase::getCurrentState());

    return $query;
  }
}
