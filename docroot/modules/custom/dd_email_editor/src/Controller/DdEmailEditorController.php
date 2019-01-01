<?php

namespace Drupal\dd_email_editor\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dd_email_editor\Utility\CommonHelper;
use Drupal\dd_base\DdBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Email Legislator Controller.
 *
 * @package Drupal\dd_email_editor\Controller
 */
class DdEmailEditorController extends ControllerBase {
  /**
   * A function to start the email leg process.
   *
   * @return renderable array
   *   renderable array for form 
   */
  public static function find($content_type,$arg) {
    global $base_url;
    $q_string = \Drupal::request()->getQueryString();
    $embed_url = $base_url . '/'.$content_type.'/' . $arg . ($q_string ? '?' . $q_string : '');
    $build_args = array(
      'state' => DdBase::getCurrentState(),
      'embed_url' => $embed_url,
      'back_link' => CommonHelper::getReferer(),
    );
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_email_editor\Form\FindNewspapersForm',
                $build_args);
    return array(
      '#theme' => 'dd-find-newspapers',
      '#title' => '',
      '#form' => $form,
      '#items' => array('embed_url' => $embed_url),
    );
  }

  /**
   * A function to get the email links to email editors.
   *
   * @return renderable array
   *   renderable array for form 
   */
  public static function getLinks($state, $zip, $city, $street, $arg) {
    parse_str($arg, $args);
    $legs = dd_email_editor_get_contact_links($state, $zip, urldecode($city),
      urldecode($street), urldecode($args['first']), urldecode($args['last']),
      $args['email'], urldecode($args['body']), urldecode($args['subject']));
    return array(
      '#theme' => 'dd-leg-contact-links',
      '#links' => $legs['editors']);
  }

  /**
   * Get Newspapers by address.
   *
   * @param string $subject
   *   Email Subject
   * @param string $body
   *   Email Body
   * @param string $state
   *   User's state
   * @param string $zip
   *   User's zip
   * @param string $city
   *   User's city
   * @param string $street
   *   User's street
   *
   * @return array
   *   renderable array for form
   */
  public static function findByAddress(Request $request) {
    $json = $request->get('json');
    $data = json_decode($json);
    $build_args = array(
      'subject' => $data->subject,
      'body' => $data->body,
      'state' => DdBase::getCurrentState(),
      'zip' => $data->zip,
      'city' => $data->city,
      'street' => $data->street,
      'back_link' => CommonHelper::getReferer(),
      'newspapers_override' => $data->newspapers_override,
    );
    $form = \Drupal::formBuilder()
      ->getForm('Drupal\dd_email_editor\Form\FindNewspapersForm',
        $build_args);
    return array(
      '#theme' => 'dd-find-newspapers-by-address',
      '#title' => 'Letter To Editor',
      '#form' => $form,
    );
  }
}
