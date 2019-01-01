<?php
/**
 * @file
 * Contains Drupal\dd_fax_service\Utility\CommonHelper.
 */

namespace Drupal\dd_fax_service\Utility;

use Drupal\Core\Url;
use Drupal\dd_fax_service\Entity\DdFaxServiceHistory;

/**
 * Contains public helper functions for fax service.
 */
class CommonHelper {

  public static function buildLinks($sender, $legislators, $body, $subject) {
    foreach ($legislators as $house => $leg) {
      if ($house === "geo_coord") {
        continue;
      }
      $leg_name =
        ($house === "Senate" ? "Senator " : "Assemblymember ") .
                   $leg['first'] .
                   (' ' . $leg['middle'] . ' ' ? $leg['middle'] : ' ')
                   . $leg['last'];
      $arg = array('first' => urlencode($sender['first']),
                   'last' => urlencode($sender['last']),
                   'street' => urlencode($sender['street']),
                   'city' => urlencode($sender['city']),
                   'zip' => urlencode($sender['zip']),
                   'contact' => urlencode($sender['contact']),
                   'to' => urlencode($leg_name),
                   'subject' => urlencode($subject),
                   'body' => urlencode($body));
      $legislators[$house]['url'] =
        Url::fromRoute(
          'dd_fax_service.fax_form',
          array(
            'fax_num' => CommonHelper::cleanDigit($leg['fax']),
          ),
          array('arg' => http_build_query($arg)))->toString();
      $legislators[$house]['name'] = $leg_name;
      $legislators[$house]['target'] = '_blank';
    }
    return $legislators;
  }

  public static function cleanDigit($num_str) {
    $result = "";
    $strlen = strlen( $num_str );
    for( $i = 0; $i <= $strlen; $i++ ) {
      $char = substr( $num_str, $i, 1 );
      if (ctype_digit($char)) {
        $result .= $char;
      }
    }
    return $result;
  }

  public static function buildFaxData($fax_num, $to, $subject, $body, $sender) {
    $form_data = array(
      '#theme' => 'dd-fax-service-fax-form',
    );
    $form_data['#fax_num'] = $fax_num;
    $form_data['#to'] = $to;
    $form_data['#subject'] = $subject;
    $form_data['#body'] = $body;
    $form_data['#sender'] = $sender;
    return \Drupal::service('renderer')->renderRoot($form_data);
  }

  /**
   * function to get the fax service history information.
   */
  public static function getFaxServiceHistory() {
    $fax_history = current(DdFaxServiceHistory::loadByFields(array()));

    return $fax_history; 
  }
}
