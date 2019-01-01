<?php

namespace Drupal\dd_search;

use Drupal\Core\Database\Database;
use Drupal\dd_base\DdBase;
use Drupal\search_api\Entity\Index;

class DdSolrDddbSync {
  const LAST_CHANGED_FIELD = 'dr_changed_ts';

  /**
   * Get Number of Entities Not Synced.
   *
   * @param string $index_id
   *   Search API Index
   *
   * @return int
   *   Number of entities.
   */
  public static function getNumEntitiesNotSynced($index_id) {
    $entity_info = self::getEntityTypeForIndex($index_id);
    $query = Database::getConnection('default', 'default')->select('search_api_item', 'sai');
    $query->join(DdBase::getDddbName() . '.' . $entity_info->base_table, 'base', 'sai.entity_id = base.' . $entity_info->id_col);
    $query->condition('sai.index_id', $index_id);
    $query->where('sai.changed != base.' . self::LAST_CHANGED_FIELD);

    switch ($index_id) {
      case 'billdiscussion_index':
        $query->join(DdBase::getDddbName() . '.Bill', 'BillDiscussion_Bill', 'base.bid = BillDiscussion_Bill.bid');
        $query->condition('BillDiscussion_Bill.state', DdBase::getCurrentState());
        break;

      case 'bills_index':
      case 'committees_index':
      case 'currentutterance_index':
      case 'hearings_index':
        $query->condition('base.state', DdBase::getCurrentState());
        break;

      case 'persons_index':
        $query->join(DdBase::getDddbName() . '.PersonStateAffiliation', 'Person_PersonStateAffiliation', 'base.pid = Person_PersonStateAffiliation.pid');
        $query->condition('Person_PersonStateAffiliation.state', DdBase::getCurrentState());
        break;
    }
    $num = $query->countQuery()->execute()->fetchField();
    return $num;
  }

  /**
   * Sync Entities between Drupal and DDDB.
   *
   * @param string $index_id
   *   Search API Index
   *
   * @param bool $delete_entities
   *   If TRUE, will remove items from Drupal that don't exist in DDDB.
   * @param bool $report_only
   *   If FALSE, adds/deletes/updates search api items.
   *
   * @returns object
   *   Report object.
   */
  public static function syncRows($index_id, $delete_entities = FALSE, $report_only = TRUE) {
    $report = new \stdClass();

    $entity_info = self::getEntityTypeForIndex($index_id);
    $dddb_table = DdBase::getDddbName() . '.' . $entity_info->base_table;

    // Check for added rows.
    $added_entity_ids = self::getAddedEntities($index_id);
    $report->num_added_entities = count($added_entity_ids);
    $report->added_entity_ids = $added_entity_ids;
    if (!$report_only && $added_entity_ids) {
      $item_ids = [];
      foreach ($added_entity_ids as $added_entity_id) {
        // trackItemsInserted expects langcode suffix.
        $item_ids[] = $added_entity_id . ':und';
      }

      // Add rows using batch tracking, which prevents immediate indexing.
      $datasource_id = 'entity:' . $entity_info->entity_type_id;
      $index = Index::load($index_id);
      $index->startBatchTracking();
      $index->trackItemsInserted($datasource_id, $item_ids);
      $index->stopBatchTracking();
    }

    // Check for deleted rows.
    $deleted_entity_ids = self::getDeletedEntities($index_id);
    $report->num_deleted_entities = count($deleted_entity_ids);
    $report->deleted_entity_ids = $deleted_entity_ids;
    if (!$report_only && $delete_entities && $deleted_entity_ids) {
      $index = Index::load($index_id);
      foreach ($deleted_entity_ids as $deleted_entity_id) {
        // Delete entity to search index;
        $datasource_id = 'entity:' . $entity_info->entity_type_id;
        $item_ids = [$deleted_entity_id . ':und'];
        $index->trackItemsDeleted($datasource_id, $item_ids);
      }
    }

    // Sync last changed timestamps.
    if (!$report_only) {
      $query = Database::getConnection('default', 'default')->query(
        t(
          'UPDATE search_api_item sai, @dddb_table base SET sai.changed = base.@dddb_last_changed_field, sai.status = 1
         WHERE sai.index_id = \'@index_id\' AND sai.entity_id = base.@dddb_base_field AND sai.changed != base.@dddb_last_changed_field',
          [
            '@dddb_table' => $dddb_table,
            '@dddb_last_changed_field' => self::LAST_CHANGED_FIELD,
            '@dddb_base_field' => $entity_info->id_col,
            '@index_id' => $index_id
          ]
        )
      );
    }
    return $report;
  }

  /**
   * Get Entities Not In Drupal DB.
   *
   * @param string $index_id
   *   Search API Index.
   *
   * @return array
   *   Array of entities IDs.
   */
  public static function getAddedEntities($index_id) {
    $entity_info = self::getEntityTypeForIndex($index_id);
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select($entity_info->base_table, 'base');
    $query->leftJoin(DdBase::getDrupalDbName() . '.search_api_item', 'sai', 'sai.entity_id = base.' . $entity_info->id_col . ' AND sai.index_id = \'' . $index_id . '\'');

    switch ($index_id) {
      case 'billdiscussion_index':
        $query->join(DdBase::getDddbName() . '.Bill', 'BillDiscussion_Bill', 'base.bid = BillDiscussion_Bill.bid');
        $query->condition('BillDiscussion_Bill.state', DdBase::getCurrentState());
        break;

      case 'bills_index':
      case 'committees_index':
      case 'currentutterance_index':
      case 'hearings_index':
        $query->condition('base.state', DdBase::getCurrentState());
        if ($index_id == 'hearings_index') {
          // Require a video before attempting to index.
          $query->join('Video', 'v', 'v.hid = base.hid');
          $query->groupBy('base.hid');
        }
        break;

      case 'persons_index':
        $query->join(DdBase::getDddbName() . '.PersonStateAffiliation', 'Person_PersonStateAffiliation', 'base.pid = Person_PersonStateAffiliation.pid');
        $query->condition('Person_PersonStateAffiliation.state', DdBase::getCurrentState());
        break;
    }

    $query->isNull('sai.item_id');
    $query->fields('base', [$entity_info->id_col]);
    $results = $query->execute()->fetchCol();
    return $results;
  }

  /**
   * Get Entities Not In DDDDB.
   *
   * @param string $index_id
   *   Search API Index.
   *
   * @return array
   *   Array of entities IDs.
   */
  public static function getDeletedEntities($index_id) {
    $entity_info = self::getEntityTypeForIndex($index_id);
    $query = Database::getConnection('default', 'default')->select('search_api_item', 'sai');
    $query->leftJoin(DdBase::getDddbName() . '.' . $entity_info->base_table, 'base', 'sai.entity_id = base.' . $entity_info->id_col);
    $query->isNull('base.' . $entity_info->id_col);
    $query->fields('sai', ['entity_id']);
    $query->condition('sai.index_id', $index_id);
    $results = $query->execute()->fetchCol();
    return $results;
  }

  /**
   * Get Entity Type Info for Index.
   *
   * @param string $index_id
   *   Search API Index
   *
   * @return \stdClass
   *   Object with base_table, id_col, entity_type_id properties.
   */
  public static function getEntityTypeForIndex($index_id) {
    $index = Index::load($index_id);
    $datasources = $index->getDatasourceIds();
    list($type, $entity_type_id) = explode(':', $datasources[0]);

    $etm = \Drupal::entityTypeManager();
    $definition = $etm->getDefinition($entity_type_id);

    $entity_info = new \stdClass();
    $entity_info->base_table = $definition->getBaseTable();
    $entity_info->id_col = $definition->getKey('id');
    $entity_info->entity_type_id = $entity_type_id;

    return $entity_info;
  }
}
