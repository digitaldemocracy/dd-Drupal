<?php

namespace Drupal\dd_committee\Entity\Sql;

use Drupal\Core\Database\Query\Select;
use Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage;
use Drupal\dd_base\DdBase;

/**
 * Class DdCommitteeSqlContentEntityStorage
 * @package Drupal\dd_committee\Entity\Sql
 */
class DdCommitteeSqlContentEntityStorage extends DdBaseStateFieldSqlContentEntityStorage  {
  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function buildQuery($ids, $revision_id = FALSE) {
    $query = parent::buildQuery($ids, $revision_id);
    $query->leftJoin('House', 'h', 'base.house = h.name');
    $query->addField('h', 'type', 'house_type');
    $query->join('CommitteeNames', 'cn', 'base.name = cn.name and base.house = cn.house and base.state = cn.state');
    $query->addField('cn', 'cn_id');

    return $query;
  }

  /**
   * Do buildQuery override to add tables.
   * @inheritdoc
   */
  public function loadMultiple(array $ids = NULL) {
    $entities = parent::loadMultiple($ids);

    if ($entities) {
      foreach ($entities as $entity) {
        // Look up Committee Name ID.
        $query = \Drupal\Core\Database\Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())
          ->select('CommitteeNames', 'cn');
        $query->fields('cn', ['cn_id']);
        $query->condition('cn.name', $entity->getName());
        $query->condition('cn.house', $entity->getHouse());
        $query->condition('cn.state', DdBase::getCurrentState());
        $cn_id = $query->execute()->fetchField();
        $entity->setCommitteeNameId($cn_id);
      }
    }

    return $entities;
  }
}
