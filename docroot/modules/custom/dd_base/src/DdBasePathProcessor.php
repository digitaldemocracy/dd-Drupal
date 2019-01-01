<?php
namespace Drupal\dd_base;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Path processor for Entities/Views.
 */
class DdBasePathProcessor implements InboundPathProcessorInterface {

  /**
   * @inheritdoc
   */
  public function processInbound($path, Request $request) {
    // Force session year facet to be set, default to "current" year if not present.
    $parameters = $request->query->all();
    if (count($parameters) == 0 && ($path == '/bills' || $path == '/hearings')) {
      $default_session_year = \Drupal::config('dd_admin.DdAdminSiteSettings')->get('default_session_year');
      if ($default_session_year) {
        $year_set = false;
        $facets = \Drupal::request()->query->get('f');

        if (!is_null($facets)) {
          // At least one facet is present, see if session_year is already set.
          foreach ($facets as $facet) {
            if (preg_match("/session_year/i", $facet)) {
              $year_set = true;
            }
          }
        }

        if (!$year_set) {
          // Default to current session year, which is the most recent odd-numbered year.
          $default_year = (int) date('Y');
          if ($default_year % 2 == 0) {
            $default_year--;
          }
          $facets[] = 'session_year:' . $default_year;
          \Drupal::request()->query->set('f', $facets);
        }
      }
    }

    return $path;
  }

}
