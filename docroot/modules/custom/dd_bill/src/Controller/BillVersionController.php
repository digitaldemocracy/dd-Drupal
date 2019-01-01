<?php

namespace Drupal\dd_bill\Controller;

use Drupal\dd_bill\Entity\DdBillVersionInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Controller\ControllerBase;

/**
 * Full text and digest callbacks for Bill Version entity.
 */
class BillVersionController extends ControllerBase {

  /**
   * Return the full text.
   *
   * @param DdBillVersionInterface $dd_bill_version
   *   The DD Bill Version Entity.
   * @param Request $request
   *   The request object.
   *
   * @return array
   *   The render array.
   */
  public function fullText(DdBillVersionInterface $dd_bill_version, Request $request) {
    return [
      '#type' => 'markup',
      '#markup' => $dd_bill_version->getText(),
    ];
  }

  /**
   * Return the full digest.
   *
   * @param DdBillVersionInterface $dd_bill_version
   *   The DD Bill Version Entity.
   * @param Request $request
   *   The request object.
   *
   * @return array
   *   The render array.
   */
  public function fullDigest(DdBillVersionInterface $dd_bill_version, Request $request) {
    return [
      '#type' => 'markup',
      '#markup' => $dd_bill_version->getDigest(),
    ];
  }
}