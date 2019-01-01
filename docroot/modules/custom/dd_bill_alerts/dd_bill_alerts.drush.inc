<?php

/**
 * @file
 * Drush commands for DD Bill Alerts.
 * @author dgirerd
 * updated: Dec 2016
 */
require_once('dd_bill_alerts.module');


/**
 * Implements hook_drush_command().
 * Specifies which drush commands the module makes available,
 * COMMANDFILE_drush_command() 
http://docs.drush.org/en/master/commands/#implement-commandfile95drush95command 
 */
function dd_bill_alerts_drush_command() {
  $items['run-bill-alerts'] = array(
    'description' => 'Executes dd bill alerts module.',
    'aliases' => array('ddba') /* "drush ddba" on command line will run this */
    ); // "drush @dd8.local ddba" to get links to work properly
  return $items;
}
/**
 * Implements drush_hook_COMMAND().
 * The function name should be same as command name but with dashes turned to
 * underscores and 'drush_commandfile_' prepended, where 'commandfile' is
 * taken from the file 'commandfile.drush.inc'
 */
function drush_dd_bill_alerts_run_bill_alerts() {
  echo "Drush running bill alerts\n";
 
  dd_bill_alerts_run();
}

