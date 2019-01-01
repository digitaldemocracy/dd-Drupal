<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_hearing\Entity\DdHearingAgenda;
use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_bill_alerts\Hearing;

class HearingContainer {

  private $scheduled_hearings = [];
  private $has_result = FALSE; // Bool

  function __construct($bid, $offset) {
    $since_date = $offset; // @debug

    // Get all upcoming hearings
    $field_values = [
      ['field' => 'date_ts', 'value' => strtotime($since_date), 'op' => '>='],
    ];
    #echo "Hearing Alert\n";
    #echo print_r($field_values,true);
    #echo "Hearing Alert";
    $hearings = DdHearing::loadByFields($field_values);

    // Loop through each hearing
    foreach($hearings as $hearing) {
      $hid = $hearing->id();

      // Get current hearing agenda for that hearing and the bill
      $field_values = [
        ['field' => 'hid', 'value' => $hid],
        ['field' => 'bid', 'value' => $bid],
        ['field' => 'current_flag', 'value' => 1], 
      ];
      $agendas = DdHearingAgenda::loadByFields($field_values);
      $agenda = current($agendas);

      // If there is an agenda make a Hearing object and put it in the array
      if ($agenda) {
        $date = date('m/d/Y', $hearing->getDate()); 
        $cn_id = $hearing->getCommitteeNameIds();
        $name = DdCommittee::loadCommitteeByNameId($cn_id)->getName();
        $this->scheduled_hearings[] = new Hearing($date, $cn_id, $name);
        $this->has_result = TRUE;
      }
    }
  }

  function getHearings() {
    return $this->scheduled_hearings;
  }

  function hasResult() {
    return $this->has_result;
  }

}
