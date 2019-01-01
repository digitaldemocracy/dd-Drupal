<?php
/**
 * @file
 * Contains Drupal\dd_find_legislators\Utility\CommonHelper.
 */

namespace Drupal\dd_find_legislators\Utility;

/**
 * contains public helper functions common to find legislators module
 */
class CommonHelper {

  public static $restUrl = "http://geo.digitaldemocracy.org/api/v1.1/find_legislators";

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
    $context_options = array(
      'http' => array(
        'method' => 'POST',
        'timeout' => 1200,
      ),
    );

    $data = json_encode($address);
    $context_options['http']['content'] = $data;
    $context_options['http']['header'] = "dataType: json\r\n"
      . "Content-type: application/json\r\n"
      . "Content-Length: " . strlen($data) . "\r\n";

    $context = stream_context_create($context_options);
    $request_url = CommonHelper::$restUrl;
    $json_result = file_get_contents($request_url, FALSE, $context);
    if ($json_result === False) {
      return Null;
    }
    return json_decode($json_result, TRUE);
  }
}
