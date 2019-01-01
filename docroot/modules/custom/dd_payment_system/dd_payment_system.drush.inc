<?php

require_once('dd_payment_system.module');
/**
 * @file
 * Drush commands for Commentary Alert.
 */

/**
 * Implements hook_drush_command().
 */
function dd_payment_system_drush_command() {
  $items['payment-system'] = array(
    'description' => 'Drush command for DD payment system.',
    'aliases' => array('pay'),
  );
  return $items;
}

/**
 * A function to be called via drush command().
 */
function drush_dd_payment_system_payment_system() {
  dd_payment_system_run();
}

