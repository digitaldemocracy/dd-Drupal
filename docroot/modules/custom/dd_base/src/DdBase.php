<?php

namespace Drupal\dd_base;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DdBase
 *
 * Contains functions to be globally used by dd_* modules.
 * @package Drupal\dd_base
 */
class DdBase {
  protected static $activeStates = [
    'CA' => 'California',
    'FL' => 'Florida',
    'TX' => 'Texas',
    'NY' => 'New York'
  ];

  /**
   * Return current state for codebase.
   *
   * @param bool $abbrev
   *   If FALSE, returns full name otherwise abbreviation.
   *
   * @return string
   *   Current State.
   */
  public static function getCurrentState($abbrev = TRUE) {
    global $_dd_env;
    return $abbrev ? $_dd_env->getState() : self::$activeStates[$_dd_env->getState()];
  }

  /**
   * Get a list of active states.
   *
   * @param bool $abbrev
   *   If FALSE, returns full name otherwise abbreviation.
   *
   * @return array
   *   Array of state abbreviations.
   */
  public static function getActiveStates($abbrev = TRUE) {
    return $abbrev ? array_keys(self::$activeStates) : array_values(self::$activeStates);
  }

  /**
   * Return current site type for codebase.
   *
   * @return string
   *   Current Site Type.
   */
  public static function getSiteType() {
    global $_dd_env;
    return $_dd_env->getSiteType();
  }

  /**
   * Return current site environment.
   *
   * @return string
   *   Current Site Environment.
   */
  public static function getEnv() {
    global $_dd_env;
    return $_dd_env->getEnv();
  }

  /**
   * Return current site environment debug info.
   *
   * @return string
   *   Current Site Environment Info output.
   */
  public static function getEnvInfo() {
    global $_dd_env;
    return $_dd_env->getEnvInfo();
  }

  /**
   * Return current site whitelabel machine id.
   *
   * @return string
   *   Current Whitelabel Machine ID.
   */
  public static function getWhiteLabelId() {
    global $_dd_env;
    return $_dd_env->getWhiteLabelId();
  }

  /**
   * Get current dddb name.
   *
   * @return string
   *   DB name.
   */
  public static function getDddbName() {
    global $_dd_env;
    return $_dd_env->getDddbName();

  }

  /**
   * Get current Drupal name.
   *
   * @return string
   *   DB name.
   */
  public static function getDrupalDbName() {
    global $_dd_env;
    return $_dd_env->getDrupalDbName();

  }

  /**
   * Get Session Year for year based on state.
   *
   * @param int $year
   *   Year
   *
   * @return int
   *   Session year for current state.
   */
  public static function getSessionYear($year) {
    if (self::getCurrentState() == 'CA' ||
        self::getCurrentState() == 'NY' ||
        self::getCurrentState() == 'TX') {
      if ($year % 2) {
        // Odd year, use previous year.
        return $year;
      }
      else {
        // Even year, use previous year.
        return $year - 1;
      }
    }
    else {
      return $year;
    }
  }

  /**
   * Get domains for state sites.
   *
   * @param bool $get_urls
   *   If TRUE, will return URLs for sites.
   * @param bool $use_current_path
   *   if TRUE, will append current path to URLs.
   * @param bool $use_query_params
   *   if TRUE, will append query params to URLs.
   *
   * @return array
   *   Array indexed by state abbrev of domains or URLs.
   */
  public static function getStateDomains($get_urls = FALSE, $use_current_path = FALSE, $use_query_params = FALSE) {
    $base_domain = 'digitaldemocracy.org';
    $state_domain_urls = [];
    $url_prefix = '';
    $url_suffix = '';
    $query_params = [];

    if ($get_urls) {
      $url_prefix = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';
    }

    if ($use_current_path) {
      $url = Url::fromRoute('<current>');
      $url_suffix = $url->toString();
    }

    if ($use_query_params) {
      $query_params = \Drupal::request()->query->all();
    }

    foreach (self::getActiveStates() as $state) {
      // For NY, some paths are hidden - redirect to home.
      if ($state !== 'CA') {
        $redirect_to_home = [
          '/commentary',
          '/commentators',
          '/analysis',
          '/analysis/gifts',
          '/analysis/contributions',
          '/analysis/alignments',
        ];

        if (in_array($url_suffix, $redirect_to_home)) {
          $url_suffix = '';
        }
      }
      // Check for interior pages to redirect.
      $interior_redirects = [
        '/person/' => '/persons',
        '/hearing/' => '/hearings',
        '/bill/' => '/bills',
        '/organization/' => '/organizations',
        '/committee/' => '/committees',
      ];

      if (preg_match('~' . implode('|', array_keys($interior_redirects)) . '~', $url_suffix, $matches)) {
        $url_suffix = $interior_redirects[$matches[0]];
      }

      if (self::getEnv() == DdEnvironment::DD_ENVIRONMENT_LOCAL || self::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
        // @todo Determine a way to switch states via url or query arg.
        // Forcing Whitelabel sites to just use base domain.
        $base_domain = \Drupal::request()->getHost();
        $url_string = $url_prefix . $base_domain . $url_suffix;
        if ($get_urls) {
          $url = Url::fromUri($url_string, ['query' => $query_params])
            ->toString();
        }
        else {
          $url = $url_string;
        }
      }
      else {
        $env_suffix = '';

        switch (self::getEnv()) {
          case DdEnvironment::DD_ENVIRONMENT_DEV:
            $env_suffix = '-dev';
            break;

          case DdEnvironment::DD_ENVIRONMENT_QA:
            $env_suffix = '-qa';
            break;

          case DdEnvironment::DD_ENVIRONMENT_PROD:
            $env_suffix = '';
            break;
        }
        $url_string = $url_prefix . $base_domain . $url_suffix;

        if ($get_urls) {
          $url = Url::fromUri($url_prefix . strtolower($state) . $env_suffix . '.' . $base_domain . $url_suffix, ['query' => $query_params])
            ->toString();
        }
        else {
          $url = $url_string;
        }
      }
      $state_domain_urls[$state] = $url;
    }
    return $state_domain_urls;
  }

  /**
   * Check if function call stack called by a certain class.
   *
   * @param array $class_names
   *   Array of class name to check ie. 'Drupal\search_api\Entity\Index'
   *
   * @return bool
   *   TRUE, if calling class found in call stack, FALSE otherwise.
   */
  public static function checkCallingClass($class_names) {
    $bt = debug_backtrace();
    foreach ($bt as $trace) {
      if (isset ($trace['class']) && in_array($trace['class'], $class_names)) {
        return TRUE;
      }
    }
    return FALSE;
  }
}
