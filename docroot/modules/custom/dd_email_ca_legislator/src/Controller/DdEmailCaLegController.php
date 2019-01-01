<?php

namespace Drupal\dd_email_ca_legislator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dd_email_legislator\Utility\CommonHelper;
use Drupal\dd_base\DdBase;
use Drupal\dd_person\Entity\DdPerson;

/**
 * Class Email Legislator Controller.
 *
 * @package Drupal\dd_email_ca_legislator\Controller
 */
class DdEmailCaLegController extends ControllerBase {

  /**
   * A function to start the email leg process.
   *
   * @return renderable array
   *   renderable array for form 
   */
  public static function contact($house, $district, $pid) {
    global $base_url;
    $arg = \Drupal::request()->getQueryString(); 
    parse_str($arg, $args);
    if (!($district && $pid && isset($args['zip']) && isset($args['city']) &&
          isset($args['street']))) {
      $msg = 'Error: unable to get the district, legislator id, or sender address.';
      return array(
        '#theme' => 'dd-email-ca-error',
        '#message' => $msg,
      );
    }

    $leg = DdPerson::load($pid);
    /*$full_name = $leg->getFirst() . ' '
               . ($leg->getMiddle() ? $leg->getMiddle() . ' ' : '')
               . $leg->getLast();*/
    $items = array(
      'leg_name' => $leg->label(),
      'title' => $house === "Senate" ? 'Senator ' : 'Assembly Member ',
      'district' => ($house == "Senate" ? 'SD' : 'AD') . $district,
      'address' => array(
        'state' => 'CA',
        'zipcode' => $args['zip'],
        'city' => urldecode($args['city']),
        'street' => urldecode($args['street']),
        'first' => urldecode($args['first']),
        'last' => urldecode($args['last']),
        'email' => urldecode($args['email']),
      ),
      'message' => urldecode($args['message']),
    );
    return array(
      '#theme' => 'dd-email-ca-leg',
      '#items' => $items,
    );
  }

  /**
   * A function to autofill the email my legislator page.
   * @param String
   *   The district number of the legislator
   *
   * @return String
   *   The title of the page
   */
  public static function getTitle($district) {
    $arg_str = \Drupal::request()->getQueryString();
    parse_str($arg_str, $args);
    return 'Contact ' . urldecode($args['name']) . ' - ' . $district;
  }
}

