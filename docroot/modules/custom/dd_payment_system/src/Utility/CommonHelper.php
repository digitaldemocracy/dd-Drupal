<?php
/**
 * @file
 * Contains Drupal\dd_payment_system\Utility\CommonHelper.
 */

namespace Drupal\dd_payment_system\Utility;

use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\dd_payment_system\Entity\DdSubscriptionPlan;
use Drupal\user\UserInterface;
use Drupal\field\FieldConfigInterface;
use Drupal\dd_fax_service_payment\Entity\DdFaxServicePaymentEntity;

/**
 * contains public helper functions common to video editor module
 * also contains some constant values used by all video editor module
 */
class CommonHelper {

  /**
   * a helper function to get all available plans.
   *
   * @return array
   *   associative array of plans 
   */
  public static function getPlans() {
    $plans = array();
    $objs = DdSubscriptionPlan::loadByFields(array());
    foreach ($objs as $obj) {
      if (!$obj->field_role_name->first()) continue;
      $plans[$obj->field_role_name->first()->value] = array(
        'name' => $obj->getName(),
        'price' => $obj->field_price->first()->value,
        'description' => $obj->field_description->first()->value,
        'duration' => $obj->field_duration_days->first()->value,
        'disable' => $obj->field_disable->value,
      );
    }
    return $plans;
  }

  public static function getFaxPaymentOptions() {
    $fax_ops = array();
    $objs = DdFaxServicePaymentEntity::loadByFields(array());
    foreach ($objs as $obj) {
      $fax_ops[] = array(
        'name' => $obj->getName(),
        'increase_limit' => $obj->field_increase_limit->value,
        'price' => $obj->field_price->first()->value,
        'duration' => $obj->field_duration->first()->value,
      );
    }
    return $fax_ops;
  }

  /**
   * a helper function to create radio group plan options.
   *
   * @param array $plans
   *   associative array of plans
   *
   * @return array
   *   associative array of options 
   */
  public static function getPlanOptions($plans) {
    $options = array();
    foreach ($plans as $key => $plan) {
      if ($plan['disable'] == false) {
        $options[$plan['name']] = $plan['name'];
      }
    }
    return $options;
  }

  public static function getFaxPaymentRadios($fax_options) {
    $options = array();
    foreach ($fax_options as $option) {
      $options[$option['name']] = 
        t($option['name'] . "<br> Increase Limit By: " .
        $option['increase_limit'] . "<br>Price: $" . 
        $option['price'] . "<br> Duration: " . 
        $option['duration'] . " days");
    }
    return $options;
  }

  /**
   * a helper function to get a default option of a plan for the user.
   *
   * @param UserInterface $user_interface
   *   UserInterface object of the user
   *
   * @param array $plans
   *   associative array of plans
   *
   * @return string 
   *   subscription plan name the user has or the first plan in the plans list 
   */
  public static function getUserPlan($user_interface, $plans) {
    foreach ($user_interface->getRoles() as $role) {
      if (isset($plans[$role])) {
        return $plans[$role]['name'];
      }
    }
    return current($plans)['name'];
  }

  /**
   * Get plan logged in user is subscribed to.
   *
   * @return string
   *   Subscription plan name user is subscribed to or null otherwise.
   */
  public static function getUserSubscribedPlan() {
    $user_roles = \Drupal::currentUser()->getRoles();
    $plans = self::getPlans();

    if ($user_roles) {
      foreach ($user_roles as $role) {
        if (isset($plans[$role])) {
          return $plans[$role]['name'];
        }
      }
    }
    return NULL;
  }

  /**
   * a helper function to calculate new expiration date.
   *
   * @param AccountInterface $user
   *   the current user
   *
   * @param array $plans
   *   associative array of plans
   *
   * @return datetime 
   *   new expiration date 
   */
  public static function getExpirationDate($user, $duration) {
    $user_interface = User::load($user->id());
    $cur_exp_date = $user_interface->field_expiration_date->first() ?
                    strtotime($user_interface->field_expiration_date->first()->value)
                    : strtotime("now");
    if ($cur_exp_date < strtotime("now")) {
      $cur_exp_date = strtotime("now");
    }
    $str_time = date("Y-m-d", $cur_exp_date) . " + " . $duration . " days 23 hours 45 minutes";
    $exp_date = strtotime($str_time);
    return date('Y-m-d\TH:i:s', $exp_date);
  }

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
}

