<?php

namespace Drupal\dd_base\Commands;

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
class DdBaseCommands extends DrushCommands {

  /**
   * Get DdEnvironment information.
   *
   * @usage drush get-dd-env
   *   Show DdEnvironment information.
   * @usage drush gde
   *   Alias to show DdEnvironment information.
   *
   * @command get:dd-env
   * @aliases gde,get-dd-env
   */
  public function ddEnv() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    $this->output()->writeln(\Drupal\dd_base\DdBase::getEnvInfo());
  }

  /**
   * Get DdEnvironment Site Type (base/state/whitelabel etc).
   *
   * @usage drush get-dd-site-type
   *   Show DdEnvironment Site Type.
   * @usage drush gdst
   *   Alias to show DdEnvironment Site Type.
   *
   * @command get:dd-site-type
   * @aliases gdst,get-dd-site-type
   */
  public function ddSiteType() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    $this->output()->writeln(\Drupal\dd_base\DdBase::getSiteType());
  }

  /**
   * Get DdEnvironment Whitelabel ID.
   *
   * @usage drush get-dd-whitelabel-id
   *   Get DdEnvironment Whitelabel ID.
   * @usage drush gdwi
   *   Alias to show DdEnvironment Whitelabel ID.
   *
   * @command get:dd-whitelabel-id
   * @aliases gdwi,get-dd-whitelabel-id
   */
  public function ddWhitelabelId() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    $this->output()->writeln(\Drupal\dd_base\DdBase::getWhiteLabelId(), 0, NULL, FALSE);
  }

  /**
   * Get DdEnvironment State.
   *
   * @usage drush get-dd-state
   *   Show DdEnvironment State.
   * @usage drush gds
   *   Alias to show DdEnvironment State.
   *
   * @command get:dd-state
   * @aliases gds,get-dd-state
   */
  public function ddState() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    $this->output()->writeln(\Drupal\dd_base\DdBase::getCurrentState());
  }
  
  /**
   * Move Common DB Tables from Drupal DB to Common DB
   *
   * @param $mysql_user
   *   MySQL User
   * @param $mysql_password
   *   MySQL Password
   * @usage drush move-common-db-tables
   *   Move Common DB Tables from Drupal DB to Common DB.
   * @usage drush mcdt
   *   Alias to Move Common DB Tables from Drupal DB to Common DB.
   *
   * @command move:common-db-tables
   * @aliases mcdt,move-common-db-tables
   */
  public function commonDbTables($mysql_user, $mysql_password) {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    global $_dd_env;
    $cmds = '';

    if ($mysql_user == '') {
      throw new \Exception(dt('No MySQL User specified.'));
    }

    if ($mysql_password != '') {
      $mysql_password = '-p' . $mysql_password;
    }

    $common_db_name = $_dd_env->getCommonDbName();
    $source_db_name = $_dd_env->getDrupalDbName();
    $common_db_tables = $_dd_env->getCommonDbTables();
    $common_db_tables_string = '  ' . implode("\n  ", $_dd_env->getCommonDbTables());

    $this->output()->writeln("Moving tables from {$source_db_name} to {$common_db_name}:");
    $this->output()->writeln($common_db_tables_string);

    $this->output()->writeln("\nGenerated SQL Commands:\n");
    // Create database.
    $cmd = "mysql -u{$mysql_user} {$mysql_password} -e \"CREATE DATABASE IF NOT EXISTS {$common_db_name}; GRANT ALL PRIVILEGES ON {$common_db_name}.* TO '{$_dd_env->getDrupalDbUsername()}'@'localhost' IDENTIFIED BY '{$_dd_env->getDrupalDbPassword()}';\"";
    $cmds .= $cmd;
    $this->output()->writeln($cmd . "\n");

    // Mysqldump tables.
    $db_file = "common_tables.sql";
    $cmd = "mysqldump -u{$mysql_user} {$mysql_password} {$source_db_name} " . implode(' ', $common_db_tables) . "> {$db_file}";
    $cmds .= ' && ' . $cmd;
    $this->output()->writeln($cmd . "\n");

    // Restore tables.
    $cmd = "mysql -u{$mysql_user} {$mysql_password} {$common_db_name} < {$db_file}";
    $cmds .= ' && ' . $cmd;
    $this->output()->writeln($cmd . "\n");

    if ($this->io()->confirm("Do you wish to continue?")) {
      drush_shell_exec($cmds);
    }

    // Rename old tables.
    $cmd = "mysql -u{$mysql_user} {$mysql_password} {$source_db_name} -e '";
    foreach ($common_db_tables as $common_db_table) {
      $cmd .= "RENAME TABLE {$common_db_table} TO moved_to_common_{$common_db_table};";
    }
    $cmd .= "'";
    $this->output()->writeln($cmd . "\n");
    if ($this->io()->confirm("Do you wish to rename moved tables?")) {
      drush_shell_exec($cmd);
    }
    return 0;
  }

  /**
   * Reset Drupal Application State dddb field schema to current dddb entity field schema
   *
   * @usage drush reset-dddb-field-schema
   *   Set Application State schema to current entity schema
   * @usage drush rdfs
   *   Alias To Set Application State schema to current entity schema
   *
   * @command reset:dddb-field-schema
   * @aliases rdfs,reset-dddb-field-schema
   */
  public function dddbFieldSchema() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
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
        $this->output()->writeln(t('...Reset Entity type @entity_type', ['@entity_type' => $entity_type_id]));
      }
    }
  }
}
