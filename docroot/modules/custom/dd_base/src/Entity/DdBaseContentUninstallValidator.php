<?php

namespace Drupal\dd_base\Entity;

use Drupal\Core\Entity\ContentUninstallValidator;

/**
 * Overrides uninstall validator to force it to pass if entity still has data.
 */
class DdBaseContentUninstallValidator extends ContentUninstallValidator {
  /**
   * @inheritdoc
   */
  public function validate($module) {
    if (strpos($module, 'dd_') !== FALSE) {
      return array();
    }

    return parent::validate($module);
  }
}
