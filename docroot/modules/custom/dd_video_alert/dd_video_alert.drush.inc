<?php

require_once('dd_video_alert.module');
/**
 * @file
 * Drush commands for Test Solr Search.
 */

/**
 * Implements hook_drush_command().
 */
function dd_video_alert_drush_command() {
  $items['video-alert'] = array(
    'description' => 'Drush command for DD video alert.',
    'aliases' => array('via'),
  );
  return $items;
}

function drush_dd_video_alert_video_alert() {
  //echo "in video alert drush command\n";
  //dd_video_alert_test();
  dd_video_alert_run();
}

