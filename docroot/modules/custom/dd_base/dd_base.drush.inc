<?php

/**
 * @file
 * Drush commands for DD Base.
 */

function dd_base_drush_command() {
  $items = array();

  $items['get-dd-env'] = array(
    'description' => 'Get DdEnvironment information.',
    'examples' => array(
      'drush get-dd-env' => dt('Show DdEnvironment information.'),
      'drush gde' => dt('Alias to show DdEnvironment information.'),
    ),
    'aliases' => array('gde'),
  );

  $items['get-dd-site-type'] = array(
    'description' => 'Get DdEnvironment Site Type (base/state/whitelabel etc).',
    'examples' => array(
      'drush get-dd-site-type' => dt('Show DdEnvironment Site Type.'),
      'drush gdst' => dt('Alias to show DdEnvironment Site Type.'),
    ),
    'aliases' => array('gdst'),
  );
  $items['get-dd-whitelabel-id'] = array(
    'description' => 'Get DdEnvironment Whitelabel ID.',
    'examples' => array(
      'drush get-dd-whitelabel-id' => dt('Get DdEnvironment Whitelabel ID.'),
      'drush gdwi' => dt('Alias to show DdEnvironment Whitelabel ID.'),
    ),
    'aliases' => array('gdwi'),
  );

  $items['get-dd-state'] = array(
    'description' => 'Get DdEnvironment State.',
    'examples' => array(
      'drush get-dd-state' => dt('Show DdEnvironment State.'),
      'drush gds' => dt('Alias to show DdEnvironment State.'),
    ),
    'aliases' => array('gds'),
  );

  $items['move-common-db-tables'] = array(
    'description' => 'Move Common DB Tables from Drupal DB to Common DB',
    'examples' => array(
      'drush move-common-db-tables' => dt('Move Common DB Tables from Drupal DB to Common DB.'),
      'drush mcdt' => dt('Alias to Move Common DB Tables from Drupal DB to Common DB.'),
    ),
    'aliases' => array('mcdt'),
    'arguments' => array(
      'mysql_user' => 'MySQL User',
      'mysql_password' => 'MySQL Password',
    ),
  );

  $items['reset-dddb-field-schema'] = array(
    'description' => 'Reset Drupal Application State dddb field schema to current dddb entity field schema',
    'examples' => array(
      'drush reset-dddb-field-schema' => dt('Set Application State schema to current entity schema'),
      'drush rdfs' => dt('Alias To Set Application State schema to current entity schema'),
    ),
    'aliases' => array('rdfs'),
  );
  return $items;
}

/**
 * Output DdEnvironment Info.
 */
function drush_dd_base_get_dd_env() {
  drush_print(\Drupal\dd_base\DdBase::getEnvInfo());
}

/**
 * Output DdEnvironment Site Type.
 */
function drush_dd_base_get_dd_site_type() {
  drush_print(\Drupal\dd_base\DdBase::getSiteType());
}

/**
 * Output DdEnvironment Whitelabel ID.
 */
function drush_dd_base_get_dd_whitelabel_id() {
  drush_print(\Drupal\dd_base\DdBase::getWhiteLabelId(), 0, NULL, FALSE);
}

/**
 * Output DdEnvironment State.
 */
function drush_dd_base_get_dd_state() {
  drush_print(\Drupal\dd_base\DdBase::getCurrentState());
}

/**
 * Move common db tables from drupal db to common db.
 *
 * @param string $mysql_user
 *   MySQL User
 *
 * @param string $mysql_password
 *   MySQL Password
 *
 * @return int
 *   0
 */
function drush_dd_base_move_common_db_tables($mysql_user = '', $mysql_password = '') {
  global $_dd_env;
  $cmds = '';

  if ($mysql_user == '') {
    return drush_set_error(dt('No MySQL User specified.'));
  }

  if ($mysql_password != '') {
    $mysql_password = '-p' . $mysql_password;
  }

  $common_db_name = $_dd_env->getCommonDbName();
  $source_db_name = $_dd_env->getDrupalDbName();
  $common_db_tables = $_dd_env->getCommonDbTables();
  $common_db_tables_string = '  ' . implode("\n  ", $_dd_env->getCommonDbTables());

  drush_print("Moving tables from {$source_db_name} to {$common_db_name}:");
  drush_print($common_db_tables_string);

  drush_print("\nGenerated SQL Commands:\n");
  // Create database.
  $cmd = "mysql -u{$mysql_user} {$mysql_password} -e \"CREATE DATABASE IF NOT EXISTS {$common_db_name}; GRANT ALL PRIVILEGES ON {$common_db_name}.* TO '{$_dd_env->getDrupalDbUsername()}'@'localhost' IDENTIFIED BY '{$_dd_env->getDrupalDbPassword()}';\"";
  $cmds .= $cmd;
  drush_print($cmd . "\n");

  // Mysqldump tables.
  $db_file = "common_tables.sql";
  $cmd = "mysqldump -u{$mysql_user} {$mysql_password} {$source_db_name} " . implode(' ', $common_db_tables) . "> {$db_file}";
  $cmds .= ' && ' . $cmd;
  drush_print($cmd . "\n");

  // Restore tables.
  $cmd = "mysql -u{$mysql_user} {$mysql_password} {$common_db_name} < {$db_file}";
  $cmds .= ' && ' . $cmd;
  drush_print($cmd . "\n");

  if (drush_confirm("Do you wish to continue?")) {
    drush_shell_exec($cmds);
  }

  // Rename old tables.
  $cmd = "mysql -u{$mysql_user} {$mysql_password} {$source_db_name} -e '";
  foreach ($common_db_tables as $common_db_table) {
    $cmd .= "RENAME TABLE {$common_db_table} TO moved_to_common_{$common_db_table};";
  }
  $cmd .= "'";
  drush_print($cmd . "\n");
  if (drush_confirm("Do you wish to rename moved tables?")) {
    drush_shell_exec($cmd);
  }
  return 0;
}

/**
 * Drush command to reset dddb field schema.
 */
function drush_dd_base_reset_dddb_field_schema() {
  $dddb_storage_class = 'Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage';
  $entity_manager = \Drupal::entityTypeManager();
  $service = Drupal::service('entity_type.listener');

  $definitions = $entity_manager->getDefinitions();
  foreach ($definitions as $entity_type_id => $definition) {
    $entity_type = $entity_manager->getDefinition($entity_type_id);

    // Get entity's storage class and its parents to check again dddb classes.
    $storage_classes = class_parents($entity_type->getStorageClass());
    $storage_classes[] = $entity_type->getStorageClass();

    if (in_array($dddb_storage_class, $storage_classes)) {
      // Trigger the onEntityTypeCreate to reset the app schema.
      // Allows drush entity-updates to work, since dddb is read-only schema.
      $service->onEntityTypeCreate($entity_type);
      drush_print(t('...Reset Entity type @entity_type', ['@entity_type' => $entity_type_id]));
    }
  }
}

