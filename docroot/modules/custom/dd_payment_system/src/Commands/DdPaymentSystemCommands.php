<?php

namespace Drupal\dd_payment_system\Commands;

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
class DdPaymentSystemCommands extends DrushCommands {

  /**
   * Drush command for DD payment system.
   *
   *
   * @command payment:system
   * @aliases pay,payment-system
   */
  public function system() {
    dd_payment_system_run();
  }

}
