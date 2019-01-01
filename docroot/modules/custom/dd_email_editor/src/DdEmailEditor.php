<?php

namespace Drupal\dd_email_editor;

use Drupal\dd_email_editor\DdEmailEditorInterface;
use Drupal\dd_email_editor\Utility\CommonHelper;
/**
 * Class DdEmailEditor
 * 
 * Implements dd email editor interface.
 * @package Drupal\dd_email_editor
 */
class DdEmailEditor implements DdEmailEditorInterface{
  public static function findNewspapers($state, $zip, $city, $street, $range_in_miles) {
    $address = array('state' => $state,
                     'zip' => $zip,
                     'city' => $city,
                     'street' => $street,
               );

    $result = CommonHelper::findNewspapers($address, $range_in_miles);
    if (!isset($result['data'])) {
      return null;
    }
    return $result['data'];
  }

  public static function findNewspapersInState($state) {
    $result = CommonHelper::findNewspapersInState($state);
    if (!isset($result['data'])) {
      return null;
    }
    return $result['data'];
  }

  public static function getContactLinks($state, $zip, $city, $street, $range_in_miles,$subject,$body) {
    $address = array('state' => $state,
                     'zip' => $zip,
                     'city' => $city,
                     'street' => $street,
               );

    $result = CommonHelper::findNewspapers($address, $range_in_miles);
    if (!isset($result['data'])) {
      return null;
    }
    return CommonHelper::convertToEmailLinks($body, $subject, $result['data']);
  }

  public static function getContactLinksInState($state,$subject,$body) {
    $result = CommonHelper::findNewspapersInState($state);
    if (!isset($result['data'])) {
      return null;
    }
    return CommonHelper::convertToEmailLinks($body, $subject, $result['data']);
  }
}
