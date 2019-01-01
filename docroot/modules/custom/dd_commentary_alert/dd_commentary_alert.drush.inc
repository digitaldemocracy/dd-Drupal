<?php

require_once('dd_commentary_alert.module');
/**
 * @file
 * Drush commands for Commentary Alert.
 */

/**
 * Implements hook_drush_command().
 */
function dd_commentary_alert_drush_command() {
  $items['commentary-alert'] = array(
    'description' => 'Drush command for DD commentary alert.',
    'aliases' => array('ddca'),
    'options' => array(
      'timezone' => 'specifies the timezone to use.',
    ),
  );
  return $items;
}

/**
 * A function to be called via drush command().
 */
function drush_dd_commentary_alert_commentary_alert() {
  $timezone = drush_get_option('timezone', null);
  dd_commentary_alert_run($timezone);
}

