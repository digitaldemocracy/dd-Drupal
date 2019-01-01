<?php

namespace Drupal\dd_metrics\Utility;
use Drupal\dd_base\DdUser;
use Drupal\dd_find_legislators\Utility\CommonHelper;
use Drupal\dd_metrics\Entity\DdCampaignVisitor;
use Drupal\user\UserInterface;

/**
 * Class DdCampaignVisitorHelper
 * @package Drupal\dd_metrics\Utility
 */
class DdCampaignVisitorHelper {
  /**
   * Check If Campaign Visitor Exists.
   *
   * @param UserInterface $visitor_user
   *   Visitor User
   *
   * @return int
   *   ID of campaign visitor, 0 if not found.
   */
  public static function checkIfVisitorExists(UserInterface $visitor_user, $campaign_id) {
    $session = \Drupal::service('session');

    // If UID is set, default check.
    if ($visitor_user->id()) {
      $visitor_ids = \Drupal::entityQuery('dd_campaign_visitor')
        ->condition('uid', $visitor_user->id())
        ->condition('campaign_id', $campaign_id)
        ->execute();

      if ($visitor_ids) {
        return reset($visitor_ids);
      }
    }

    // Check session ID.
    if ($session->getId() != '') {
      $visitor_ids = \Drupal::entityQuery('dd_campaign_visitor')
        ->condition('session_id', $session->getId())
        ->condition('campaign_id', $campaign_id)
        ->execute();

      if ($visitor_ids) {
        return reset($visitor_ids);
      }
    }

    $dd_user = new DdUser();
    $dd_user->createFromUser($visitor_user);

    // Check name / address.
    if ($dd_user->validateFields()) {
      $visitor_ids = \Drupal::entityQuery('dd_campaign_visitor')
        ->condition('campaign_id', $campaign_id)
        ->condition('first_name', $dd_user->getFirstName())
        ->condition('last_name', $dd_user->getLastName())
        ->condition('address', $dd_user->getStreet())
        ->condition('city', $dd_user->getCity())
        ->condition('state', $dd_user->getState())
        ->condition('zip', $dd_user->getZip())
        ->execute();

      if ($visitor_ids) {
        return reset($visitor_ids);
      }
    }

    return 0;
  }

  /**
   * Create Campaign Visitor.
   *
   * Checks if visitor exists, otherwise creates new entity.
   *
   * @param UserInterface $visitor_user
   *   Visitor user.
   * @param int $campaign_id
   *   Campaign ID.
   *
   * @return int
   *   Campaign Visitor ID, or 0
   */
  public static function createCampaignVisitor(UserInterface $visitor_user, $campaign_id) {
    $session = \Drupal::service('session');
    $visitor_id = DdCampaignVisitorHelper::checkIfVisitorExists($visitor_user, $campaign_id);

    if (!$visitor_id) {
      // Create a visitor.
      $dd_user = new DdUser();
      $dd_user->createFromUser($visitor_user);

      // Check to see if user object contains districts already.
      $visitor_assembly_district = $session->get('visitor_assembly_district');
      $visitor_senate_district = $session->get('visitor_senate_district');

      // Get district data from API.
      if ((empty($visitor_assembly_district) || empty($visitor_senate_district)) && !empty($dd_user->toArray())) {
        $result = CommonHelper::findLegislators($dd_user->toArray());
        if (isset($result['data']) && isset($result['data']['legislators'])) {
          $visitor_assembly_district = $result['data']['legislators']['Assembly']['district'];
          $visitor_senate_district = $result['data']['legislators']['Senate']['district'];
        }
      }

      // @todo Determine county from address.
      $county = '';

      $data = [
        'campaign_id' => $campaign_id,
        'first_name' => $visitor_user->get('field_first_name'),
        'last_name' => $visitor_user->get('field_last_name'),
        'address' => $dd_user->getStreet(),
        'city' => $dd_user->getCity(),
        'state' => $dd_user->getState(),
        'zip' => $dd_user->getZip(),
        'county' => $county,
        'assembly_district' => $visitor_assembly_district,
        'senate_district' => $visitor_senate_district,
        'session_id' => $session->getId(),
        'uid' => $visitor_user->id() ? $visitor_user->id() : 0,
      ];
      $campaign_visitor_entity = DdCampaignVisitor::create($data);
      $campaign_visitor_entity->save();
      $visitor_id = $campaign_visitor_entity->id();
    }
    return $visitor_id;

  }
}
