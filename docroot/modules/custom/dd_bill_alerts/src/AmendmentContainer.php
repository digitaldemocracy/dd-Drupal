<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_bill\Entity\DdBillVersion;
use Drupal\dd_bill_alerts\Amendment;

class AmendmentContainer {

  private $amendments = []; // Array of Amendment objects
  private $has_result = FALSE; // Bool with default value FALSE

  function __construct($bid, $offset) {
  $since_date = $offset;

    // Get all versions of the bill
    $field_values = [
      ['field' => 'bid', 'value' => $bid],
      ['field' => 'date_ts', 'value' => strtotime("-7 days"), 'op' => '>='],
      ['field' => 'lastTouched_ts', 'value' => strtotime($since_date), 'op' => '>='],
      ];
    $amendments = DdBillVersion::loadByFields($field_values);

    // If there was an amendment, set the result flag
    if($amendments) {
      $this->has_result = TRUE;
    }

    // Loop through each amendment, creating an Amendment object, and putting it in the container 
    foreach($amendments as $amendment) {
      $this->amendments[] =
        new Amendment(date('m/d/Y', $amendment->date_ts->value),
                      $amendment->billState->value);
    }
  }

  function getAmendmentss() {
    return $this->amendments;
  }

  function hasResult() {
    return $this->has_result;
  }

}
