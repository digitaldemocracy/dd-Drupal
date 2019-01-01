<?php

namespace Drupal\dd_bill\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\dd_base\DdBase;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_bill\Entity\DdBillVersionCurrent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DefaultController.
 *
 * @package Drupal\dd_bill\Controller
 */
class DefaultController extends EntityViewController {

  /**
   * View a bill by the BID field.
   *
   * @return string
   *   Return rendered bill.
   */
  public function viewByBid($dd_bill_bid) {
    $bill_dr_id = DdBill::getBillbyBid($dd_bill_bid);
    if ($bill_dr_id) {
      $bill = DdBill::load($bill_dr_id->dr_id);
      return $this->view($bill);
    }
    else {
      return [
        '#type' => 'markup',
        '#markup' => t('Bill not found'),
      ];
    }
  }

  /**
   * @param $bill_dr_id
   *
   * @return JsonResponse
   */
  public function getBillVersionCurrentJson($bill_dr_id) {
    $bill = DdBill::load($bill_dr_id);
    if ($bill) {
      $billversion = DdBillVersionCurrent::getByBid($bill->getBid());
      if ($billversion) {
        return new JsonResponse($billversion->toArray());
      }
    }
    return new JsonResponse();
  }

  /**
   * Title callback for DdBill.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   * @param \Drupal\Core\Entity\EntityInterface $_entity
   *   (optional) An entity, passed in directly from the request attributes.
   *
   * @return string|null
   *   The title for the entity view page, if an entity was found.
   */
  public function title(RouteMatchInterface $route_match, EntityInterface $_entity = NULL) {
    if ($_entity) {
      $entity = $_entity;
    }
    else {
      $bill_dr_id = DdBill::getBillbyBid($route_match->getParameter('dd_bill_bid'));
      if ($bill_dr_id) {
        $entity = DdBill::load($bill_dr_id->dr_id);
      }
    }

    if ($entity) {
      return $entity->label();
    }
  }
}
