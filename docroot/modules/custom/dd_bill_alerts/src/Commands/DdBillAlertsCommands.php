<?php

namespace Drupal\dd_bill_alerts\Commands;

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
class DdBillAlertsCommands extends DrushCommands {

  /**
   * Executes dd bill alerts module.
   *
   *
   * @command run:bill-alerts
   * @aliases ddba,run-bill-alerts
   */
  public function billAlerts() {
    echo "Drush running bill alerts\n";

    dd_bill_alerts_run();
  }

}
