<?php

namespace Drupal\dd_email_legislator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dd_email_legislator\Utility\CommonHelper;
use Drupal\dd_base\DdBase;

/**
 * Class Email Legislator Controller.
 *
 * @package Drupal\dd_email_legislator\Controller
 */
class DdEmailLegController extends ControllerBase {
  /**
   * A function to start the email leg process.
   *
   * @return renderable array
   *   renderable array for form 
   */
  public function start($content_type,$arg) {
    global $base_url;
    $q_string = \Drupal::request()->getQueryString();
    $embed_url = $base_url . '/'.$content_type.'/' . $arg . ($q_string ? '?' . $q_string : '');
    $build_args = array(
      'state' => DdBase::getCurrentState(),
      'embed_url' => $embed_url,
      'back_link' => CommonHelper::getReferer(),
      'bcc' => $this->config('dd_admin.DdAdminEmailSettings')->get('bcc_address'),
    );
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_email_legislator\Form\FindMyLegForm',
                $build_args);
    return array(
      '#theme' => 'dd-find-my-leg',
      '#title' => '',
      '#form' => $form,
      '#items' => array('embed_url' => $embed_url),
    );
  }

  /**
   * A function to get the email links to email legislators.
   *
   * @return renderable array
   *   renderable array for form 
   */
  public static function getLinks($state, $zip, $city, $street, $arg) {
    parse_str($arg, $args);
    $legs = dd_email_legislator_get_contact_links($state, $zip, urldecode($city),
      urldecode($street), urldecode($args['first']), urldecode($args['last']),
      $args['email'], urldecode($args['body']), urldecode($args['subject']), $args['bcc']);
    return array(
      '#theme' => 'dd-leg-contact-links',
      '#links' => $legs['legislators']);
  }
}
