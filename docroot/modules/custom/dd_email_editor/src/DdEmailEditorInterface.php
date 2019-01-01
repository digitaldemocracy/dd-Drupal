<?php

namespace Drupal\dd_email_editor;
/**
 * defines the public interface for dd email editor module.
 *
 * @ingroup dd_email_editor
 */
interface DdEmailEditorInterface {
  public static function findNewspapers($state, $zip, $city, $street, $range_in_miles);  
  public static function findNewspapersInState($state);  
  public static function getContactLinks($state, $zip, $city, $street, $range_in_miles,$subject,$body);  
  public static function getContactLinksInState($state,$subject,$body);  
}
