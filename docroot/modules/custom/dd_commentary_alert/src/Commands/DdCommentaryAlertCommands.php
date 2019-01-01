<?php

namespace Drupal\dd_commentary_alert\Commands;

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
class DdCommentaryAlertCommands extends DrushCommands {

  /**
   * Drush command for DD commentary alert.
   *
    * @param array $options An associative array of options whose values come from cli, aliases, config, etc.
   * @option timezone
   *   specifies the timezone to use.
   *
   * @command commentary:alert
   * @aliases ddca,commentary-alert
   */
  public function alert(array $options = ['timezone' => null]) {
     $timezone = drush_get_option('timezone', null);
     dd_commentary_alert_run($timezone);
  }

}
