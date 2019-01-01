<?php

namespace Drupal\dd_search\Commands;

use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class DdSearchCommands extends DrushCommands {

  /**
   * Sync Solr With DDDB.
   *
   * @param $mode
   *   Mode - report (default), report_detailed, update
    * @param array $options An associative array of options whose values come from cli, aliases, config, etc.
   * @option indexes
   *   Search API Index IDs to process, comma delimited
   * @option delete
   *   Will remove rows from Search API Index that do not exist in DDDB
   * @usage drush dddb-solr-sync
   *   Sync solr with DDDB, report only.
   * @usage drush dss
   *   Alias to Sync solr with DDDB, report only.
   * @usage drush dss report_detailed
   *   Sync all indexes with DDDB, report only with details of entity IDs to be processed.
   * @usage drush dss update
   *   Sync all indexes with DDDB.
   * @usage drush dss update --indexes=bills_index,persons_index
   *   Sync bills_index and persons_index with DDDB.
   *
   * @command dddb:solr-sync
   * @aliases dss,dddb-solr-sync
   */
  public function dddb_solr_sync($mode, array $options = ['indexes' => null, 'delete' => null]) {
      // Ensure entity_id column exists.
      //  add_search_api_item_column();

  $index_ids = $options['indexes'];
  $perform_deletions = $options['delete'];

  if ($index_ids) {
    $indexes = explode(',', $index_ids);
  }
  else {
    $indexes = \Drupal\search_api\Entity\Index::loadMultiple();
    $indexes = array_keys($indexes);
  }
  foreach ($indexes as $index_id) {
    $this->output()->writeln(t('Processing Index @index', ['@index' => $index_id]));
    $report = \Drupal\dd_search\DdSolrDddbSync::syncRows($index_id);
    $this->output()->writeln(t("\tFound @num Rows Added in DDDB to sync...", ['@num' => $report->num_added_entities]));
    if ($mode == 'report_detailed') {
      $this->output()->writeln(implode(',', $report->added_entity_ids) . "\n");
    }

    $this->output()->writeln(t("\tFound @num Rows Deleted in DDDB to sync...", ['@num' => $report->num_deleted_entities]));
    if ($mode == 'report_detailed') {
      $this->output()->writeln(implode(',', $report->deleted_entity_ids) . "\n");
    }

    $num_not_synced = \Drupal\dd_search\DdSolrDddbSync::getNumEntitiesNotSynced($index_id);
    $this->output()->writeln(t("\tFound @num Rows Not Synced with DDDB last changed...", ['@num' => $num_not_synced]) . "\n");

    if ($mode == 'update' && ($report->num_added_entities || $report->num_deleted_entities || $num_not_synced)) {
      $this->output()->writeln("\n\t...Syncing entities\n");
      \Drupal\dd_search\DdSolrDddbSync::syncRows($index_id, $perform_deletions, FALSE);
    }

    \Drupal::logger('dd_search')->info('Processed @index - @added Rows Added in DDDB, @deleted Rows Deleted in DDDB, @synced Rows Updated in DDDB.',
      [
        '@index' => $index_id,
        '@added' => $report->num_added_entities,
        '@deleted' => $report->num_deleted_entities,
        '@synced' => $num_not_synced,
      ]
    );
  }

}

  /**
   * Sync Solr With Drupal Search API Items.
   *
   * @param $mode
   *   Mode - report (default), update
    * @param array $options An associative array of options whose values come from cli, aliases, config, etc.
   * @option indexes
   *   Search API Index IDs to process, comma delimited
   * @option email
   *   Email address to send report to
   * @usage drush drupal-solr-sync
   *   Sync solr with Drupal Search API Items, report only.
   * @usage drush drss
   *   Alias to Sync solr with Drupal Search API Items, report only.
   * @usage drush drss --email='user@here.com' 
   *   Alias to Sync solr with Drupal Search API Items, report only and send to email
   * @usage drush drss update
   *   Sync all indexes with Drupal.
   * @usage drush drss update --indexes=bills_index,persons_index
   *   Sync bills_index and persons_index with Drupal.
   *
   * @command drupal:solr-sync
   * @aliases drss,drupal-solr-sync
   */
  public function drupal_solr_sync($mode, array $options = ['indexes' => null, 'email' => null]) {
    $report = '';
  $index_ids = $options['indexes'];
  $email = $options['email'];
  if ($index_ids) {
    $indexes = explode(',', $index_ids);
  }
  else {
    $indexes = \Drupal\search_api\Entity\Index::loadMultiple();
    $indexes = array_keys($indexes);
  }

  foreach ($indexes as $index_id) {
    // Compare search_api_items indexed items count vs actual.

    $index = \Drupal\search_api\Entity\Index::load($index_id);
    $sai_num_items = $index->getTrackerInstance()->getIndexedItemsCount();

    $query = new \Drupal\search_api\Query\Query($index);
    $query->range(0, 0);
    $result = $query->execute();
    $solr_num_items = $result->getResultCount();

    if ($solr_num_items < $sai_num_items) {
      $report .= t('Index @index_id has less items than Drupal Search API Items (@solr < @sai)', [
        '@index_id' => $index_id,
        '@solr' => $solr_num_items,
        '@sai' => $sai_num_items
      ]) . "\n";

      // Determine which item IDs are missing for smaller indexes.
      if ($index_id != 'currentutterance_index') {
        $sai_rows = \Drupal::database()
          ->select('search_api_item', 'sai')
          ->condition('sai.index_id', $index_id)
          ->fields('sai', ['item_id', 'status'])
          ->execute()
          ->fetchAllAssoc('item_id');

        $person_query = new \Drupal\search_api\Query\Query($index);
        $solr_rows = $person_query->execute()->getResultItems();
        $unmatched_rows = array_diff_key($sai_rows, $solr_rows);
        $missing_entity_ids = [];

        if ($unmatched_rows) {
          foreach ($unmatched_rows as $unmatched_row) {
            list($type, $entity_id_langcode) = explode('/', $unmatched_row->item_id);
            list($missing_entity_ids[$unmatched_row->item_id], $langcode) = explode(':', $entity_id_langcode);
          }
          $report .= "Missing Entity IDs: \n" . implode(',', $missing_entity_ids) . "\n";
        }
      }

      if ($mode == 'update') {
        // Reindex missing items in Solr, only change status not date.
        $tracker = $index->getTrackerInstance();

        $transaction = $tracker->getDatabaseConnection()->startTransaction();
        try {
          $update = $tracker->getDatabaseConnection()->update('search_api_item')
            ->condition('index_id', $index_id);

          $update->condition('item_id', array_keys($missing_entity_ids), 'IN');
          $update->fields(array('status' => \Drupal\search_api\Plugin\search_api\tracker\Basic::STATUS_NOT_INDEXED));
          $update->execute();
        }
catch (\Exception $e) {
          watchdog_exception('dd_search', $e);
          $transaction->rollback();
          $this->output()->writeln(t('Exception on update: @e', ['@e' => $e->getMessage()]));
        }
      }
    }
    elseif ($solr_num_items > $sai_num_items) {
      $report .= t('Index @index_id has more items than Drupal Search API Items (@solr > @sai)', [
        '@index_id' => $index_id,
        '@solr' => $solr_num_items,
        '@sai' => $sai_num_items
      ]) . "\n";

      // @todo Determine action here, means items need deleting from Solr.
    }
    else {
      $report .= t('Index @index_id in sync', ['@index_id' => $index_id]) . "\n";
    }
  }
  $this->output()->writeln($report);

  if ($email != '') {
    $mail_manager = \Drupal::service('plugin.manager.mail');
    $module = 'dd_search';
    $key = 'dss_report';
    $params['message'] = $report;
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;

    $result = $mail_manager->mail($module, $key, $email, $langcode, $params, NULL, $send);
    if ($result['result'] == TRUE) {
      $this->output()->writeln(t('Report sent to @email', ['@email' => $email]));
    }
    else {
      $this->output()->writeln(t('Could not send report to @email', ['@email' => $email]));
    }
  }
  }

}

/**
 * Add entity_id column to search_api_item if missing.
 */
function add_search_api_item_column() {
  $this->output()->writeln('Checking for search_api_item.entity_id column...');
  $field_exists = \Drupal\Core\Database\Database::getConnection('default', 'default')->schema()->fieldExists('search_api_item', 'entity_id');
  if (!$field_exists) {
    $this->output()->writeln('... Field missing, creating.');
    $query_string = "ALTER TABLE search_api_item ADD COLUMN `entity_id` int(11) GENERATED ALWAYS AS (substring_index(substr(`item_id`,(locate('/',`item_id`) + 1)),':',1)) STORED;";
    \Drupal\Core\Database\Database::getConnection('default', 'default')->query($query_string);

    $query_string = "ALTER TABLE search_api_item ADD INDEX entity_id (entity_id);";
    \Drupal\Core\Database\Database::getConnection('default', 'default')->query($query_string);
  }
  else {
    $this->output()->writeln('... Field exists.');
  }
}

