<?php

namespace Drupal\dd_base;

class DdBaseGoogleAnalytics {
  /**
   * Add google analytics tracking code for DD.
   *
   * ID can be overridden by setting:
   * $config['dd_google_analytics_id'] in settings.local.php
   */
  public static function addDdAnalyticsTracking() {
    global $config;
    $default_dd_google_analytics_id = 'UA-51439651-1';
    $ga_config = \Drupal::configFactory()->getEditable('google_analytics.settings');

    // Allow settings.php to override GA account.
    $dd_ua_id = isset($config['dd_google_analytics_id']) ? $config['dd_google_analytics_id'] : $default_dd_google_analytics_id;
    if ($ga_config->get('account') !== $dd_ua_id && strpos($ga_config->get('codesnippet.after'), $dd_ua_id) === FALSE) {
      // Add in extra code to track.
      $after_code = $ga_config->get('codesnippet.after');
      $after_code .= "ga('create', '" . $dd_ua_id . "', 'auto', 'ddTracker');ga('ddTracker.send', 'pageview');";
      $ga_config->set('codesnippet.after', $after_code)->save();
    }
  }
}
