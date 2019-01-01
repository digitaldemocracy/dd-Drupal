<?php

namespace Drupal\dd_video_alert\Commands;

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
class DdVideoAlertCommands extends DrushCommands {

  /**
   * Drush command for DD video alert.
   *
   *
   * @command video:alert
   * @aliases via,video-alert
   */
  public function alert() {
  //echo "in video alert drush command\n";
  //dd_video_alert_test();
  dd_video_alert_run();
  }

}
