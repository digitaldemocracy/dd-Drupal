<?php
/**
 * @file
 * Contains Drupal\dd_email_legislator\Utility\CommonHelper.
 */

namespace Drupal\dd_email_legislator\Utility;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;

/**
 * contains public helper functions common to email legislator module
 */
class CommonHelper {

  /**
   * A helper function to get referer.
   *
   * @return string 
   *   url of the referer 
   */
  public static function getReferer() {
    $previousUrl = \Drupal::request()->server->get('HTTP_REFERER');
    return $previousUrl;
  }

  /**
   * Function to make a rest api call to find legislators.
   *
   * @param array $address
   *   assoc array with keys:state,zipcode,city,street 
   *
   * @return array
   *   array including house, district, name, contact info 
   */
  public static function findLegislators(array $address) {
    return dd_find_legislators_find($address);
  }

  /**
   * A helper function to make a message to popup on user's browser.
   *
   * @param string $msg
   *   message to show
   *
   * @return string
   *   AjaxResponse object
   */
  public static function ajaxErrorResponse($msg) {
    $response = new AjaxResponse();
    return $response->addCommand(new AlertCommand($msg));
  }
}

