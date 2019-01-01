<?php

namespace Drupal\dd_email_editor\Commands;

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
class DdEmailEditorCommands extends DrushCommands {

  /**
   * Drush command for DD Email Editor.
   *
   *
   * @command email:editor
   * @aliases ddee,email-editor
   */
  public function editor() {
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

}
