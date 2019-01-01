<?php

namespace Drupal\dd_organization\Entity\Sql;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage;

/**
 * Class DdOrganizationSqlContentEntityStorage
 * @package Drupal\dd_organization\Entity\Sql
 */
class DdOrganizationSqlContentEntityStorage extends DdBaseSqlContentEntityStorage  {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    $query->leftJoin('OrganizationStateAffiliation', 'osa', "base.oid = osa.oid AND osa.state='" . DdBase::getCurrentState() . "'");
    $query->addField('osa', 'state', 'stateAffiliated');

    return $query;
  }
}
