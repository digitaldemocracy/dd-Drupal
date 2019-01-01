<?php

require_once('dd_email_editor.module');
/**
 * @file
 * Drush commands for Commentary Alert.
 */

/**
 * Implements hook_drush_command().
 */
function dd_email_editor_drush_command() {
  $items['email-editor'] = array(
    'description' => 'Drush command for DD Email Editor.',
    'aliases' => array('ddee'),
  );
  return $items;
}

/**
 * A function to be called via drush command().
 */
function drush_dd_email_editor_email_editor() {
  $state = 'CA';
  $zip = '93405';
  $city = 'San Luis Obispo';
  $street = '1593 Frambuesa Drive';
  $range = 100;
  $result = dd_email_editor_get_newspapers($state,$zip,$city,$street,$range);
  echo print_r($result,true);
  $result = dd_email_editor_contact_links("Hello World!","Hello World",$result);
  echo print_r($result,true);
  $result = dd_email_editor_get_contact_links($state,$zip,$city,$street,$range,"Hello World!!","Hello");
  echo print_r($result,true);
}
