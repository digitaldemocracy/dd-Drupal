<?php

namespace Drupal\dd_base\Plugin\migrate\source\d7;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\user\Plugin\migrate\source\d7\User;
use Drupal\migrate\Row;

/**
 * Drupal 7 DD user source from database.
 *
 * @MigrateSource(
 *   id = "d7_dd_user"
 * )
 */
class DdUser extends User {
  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $result = parent::prepareRow($row);

    // Scrub email addresses if not on production.
    if (DdBase::getEnv() != DdEnvironment::DD_ENVIRONMENT_PROD) {
      $scrubbed_email = 'user' . $row->getSourceProperty('uid') . '@example.com';
      $row->setSourceProperty('mail', $scrubbed_email);
    }
    return $result;
  }

}
