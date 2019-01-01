<?php

namespace Drupal\dd_action_center\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_bill\Entity\DdBillVersionCurrent;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * A Dd Campaign Legislators controller.
 */
class DdCampaignLegislatorsController extends ControllerBase {

  /**
   * Get Current Legislators, filtered by targets.
   *
   * @param string $house
   *   House (Assembly / Senate)
   * @param string $party
   *   Party (Republican / Democrat)
   * @param string $committee_name
   *   Committee Name
   *
   * @return JsonResponse
   *   Sample response:
   *
   * {
   *   "count": 1,
   *   "legislators": [
   *   {
   *     "pid": "6",
   *     "first": "Autumn",
   *     "last": "Burke",
   *     "house": "Assembly",
   *     "party": "Democrat"
   *   }
   * }
   */
  public function getTargetLegislators($house = 'all', $party = 'all', $committee_name = 'all') {
    $house = ($house == 'all') ? '' : $house;
    $party = ($party == 'all') ? '' : $party;
    $committee_name = ($committee_name == 'all') ? '' : $committee_name;

    $legislators = DdLegislator::getCurrentLegislators($house, $party, $committee_name);
    $data = [
      'count' => count($legislators),
      'legislators' => $legislators,
    ];
    return new JsonResponse($data);

  }

  /**
   * Get Current Committees, filtered by house.
   *
   * @param string $house
   *   House (Assembly / Senate)
   *
   * @return JsonResponse
   *   Sample response:
   *
   * {
   *   "count": 1,
   *   "committees": [
   *   {
   *     "cid": "6",
   *     "name": "1st Assembly Committee On Budget"
   *   }
   * }
   */
  public function getTargetCommittees($house = 'all') {
    $house = ($house == 'all') ? '' : $house;

    $committees = DdCommittee::getActiveCommittees($house);

    $data = [
      'count' => count($committees),
      'committees' => $committees,
    ];
    return new JsonResponse($data);
  }

}
