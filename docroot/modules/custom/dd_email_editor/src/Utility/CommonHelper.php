<?php
/**
 * @file
 * Contains Drupal\dd_email_editor\Utility\CommonHelper.
 */

namespace Drupal\dd_email_editor\Utility;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;

/**
 * contains public helper functions common to email editor module
 */
class CommonHelper {

  public static $restUrl = "http://geo.digitaldemocracy.org/api/v1.1/";

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
   * Function to make a rest api call to find newspapers.
   *
   * @param array $address
   *   assoc array with keys:state,zipcode,city,street 
   * @param int $range_in_miles
   *   intger specifying range in miles from the address 
   *
   * @return array
   *   array including house, district, name, contact info 
   */
  public static function findNewspapers(array $address, $range_in_miles=0) {
    $context_options = array(
      'http' => array(
        'method' => 'POST',
        'timeout' => 1200,
      ),
    );

    $address['distance'] = $range_in_miles;
    $data = json_encode($address);
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";

    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl . "find_editors";
    $json_result = file_get_contents($request_url, FALSE, $context);
    if ($json_result === False) {
      return Null;
    }
    return json_decode($json_result, TRUE);
  }

  /**
   * Function to make a rest api call to find newspapers in a state.
   *
   * @param string $state
   *   string of state abbreviation 
   *
   * @return array
   *   array including house, district, name, contact info 
   */
  public static function findNewspapersInState($state) {
    $context_options = array(
      'http' => array(
        'method' => 'POST',
        'timeout' => 1200,
      ),
    );

    $data = json_encode(array('state'=>$state));
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";

    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl . "find_in_state";
    $json_result = file_get_contents($request_url, FALSE, $context);
    if ($json_result === False) {
      return Null;
    }
    return json_decode($json_result, TRUE);
  }

  /**
   * a helper function to get email links
   *
   * @param string $body
   *   sender message body
   * @param string $subject
   *   sender message subject
   * @param array $newspapers
   *   assoc array of newspapers
   *
   * @return renderable array
   *   the array including editorsi with the url for emailing and geo_coord. 
   */
  public static function convertToEmailLinks($body, $subject = null, $newspapers, $bcc=null) {
    $links = array();
    foreach ($newspapers as $newspaper) {
      $links[$newspaper['name']]['url'] =
        'mailto:' . $newspaper['email'].'?subject=' . rawurlencode($subject) .
        '&body=' . rawurlencode(html_entity_decode($body));
      if ($bcc) {
        $links[$newspaper['name']]['url'] .= '&bcc=' . rawurlencode($bcc);
      }
      $links[$newspaper['name']]['target'] = '_self';
      $links[$newspaper['name']]['name'] = $newspaper['name'];
    }
    return array('newspapers' => $links);
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

