<?php

namespace Drupal\dd_bill\Plugin\EntityReferenceSelection;

use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\dd_base\DdBase;
use Drupal\dd_bill\Entity\DdBill;

/**
 * Entity Reference Selection plugin for Dd Bill.
 *
 * @see plugin_api
 *
 * @EntityReferenceSelection(
 *   id = "default:dd_bill",
 *   label = @Translation("DD Bill Selection"),
 *   group = "default",
 *   entity_types = {"dd_bill"},
 *   weight = 1,
 * )
 */
class DdBillSelection extends DefaultSelection {

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $filtered = [];
    // @todo Ignore match conditions for now, just use current session year.
    $session_years = [DdBase::getSessionYear(date('Y'))];
    $bills = DdBill::getBillMatches($match, $limit ? $limit : 10, '', $session_years);
    if ($bills) {
      foreach ($bills as $bill) {
        $filtered['dd_bill'][$bill->dr_id] = '[' . $bill->sessionYear . '] <span class="dd-search-text">' . $bill->type . ' ' . $bill->number . '</span>: ' . $bill->subject;
      }
    }

    return $filtered;
  }
}
