<?php

namespace Drupal\dd_admin;

use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;

/**
 * Class DdAdmin
 *
 * Contains functions specific to admin functionality.
 * @package Drupal\dd_admin
 */
class DdAdmin {
  /**
   * Should Action Center Be Shown.
   * @return bool
   *   TRUE or FALSE.
   */
  public static function showActionCenter() {
    $site_type = DdBase::getSiteType();

    // Action center can only be shown on White Label sites.
    if ($site_type != DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      return FALSE;
    }

    $config = \Drupal::config(self::getDdAdminContentSettingsName());
    if ($config->get('visibility_action_center') == 'anonymous') {
      return TRUE;
    }
    elseif ($config->get('visibility_action_center') == 'authenticated' && \Drupal::currentUser()->id()) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check visibility of a site area set by admin form.
   *
   * @param string $form_field
   *   Form field from DdAdminContentSettings (ie. visibility_committees).
   *
   * @return bool
   *   TRUE if should be shown, FALSE otherwise.
   */
  public static function checkVisibility($form_field) {
    // Visibility only applies for whitelabel sites.
    if (DdBase::getSiteType() != DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
      return TRUE;
    }
    $config = \Drupal::config(self::getDdAdminContentSettingsName());
    $uid = \Drupal::currentUser()->id();
    return ($config->get($form_field) == 'anonymous' || ($uid && $config->get($form_field) == 'authenticated'));
  }

  /**
   * Get DdAdminContentSettings path, general or specific to whitelabel id.
   *
   * @return string
   *   Config settings path.
   */
  public static function getDdAdminContentSettingsName() {
    $settings_path = 'dd_admin.DdAdminContentSettings';
    $id = DdBase::getWhiteLabelId();
    if ($id != '') {
      $settings_path .= '.' . $id;
    }
    return $settings_path;
  }
}
