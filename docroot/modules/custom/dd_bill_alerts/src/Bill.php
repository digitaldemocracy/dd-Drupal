<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_bill_alerts\VoteContainer;
use Drupal\dd_bill_alerts\HearingContainer;
use Drupal\dd_bill_alerts\VideoContainer;
use Drupal\dd_bill_alerts\AmendmentContainer;
use \Exception;

class Bill {
  
  private $type; // string
  private $number; // string
  private $id; // string
  private $link; // string
  private $vote; // VoteContainer
  private $hearing; // HearingContainer
  private $video; // VideoContainer
  private $amendment; // AmendmentContainer

  function __construct($type, $number, $state, $session_year) {
    // Check that bill type (AB) is correct. Automatically for appropriate state.
    if (!in_array($type, DdBill::getBillTypes())) {
      throw new Exception('Invalid type passed to Bill constructor.'); 
    }

    $this->type = $type;
    $this->number = $number;
    $this->id = Bill::lookup_bill_id($type, $number, $state, $session_year); 
    $this->link = $GLOBALS['base_url'] . "/bill/" . $this->id;
  }

  // Get the bill id for the bill
  static function lookup_bill_id($bill_type, $bill_number, $state, $session_year) {
    #echo $state . " " . $session_year . "\n";    
    // Get list of potential matches from Drupal
    $bills = DdBill::getBillMatches($bill_type . $bill_number, 999, $state, [$session_year]);

    // Loop through list and match exactly
    foreach ($bills as $bill) {
      if ($bill->type == $bill_type && $bill->number == $bill_number) {
        return $bill->bid;
      }
    }

    // If wasn't found throw exception because should always have a match
    throw new Exception('Invalid bill passed to lookup_bill_id.');
  }

  function getType() {
    return $this->type;
  }

  function getNumber() {
    return $this->number;
  }

  function getId() {
    return $this->id;
  }

  function getName() {
    return $this->type . $this->number;
  }

  function getLink() {
    return $this->link;
  }

  function getVoteContainer($offset) {
    if(!isset($this->vote)) {
      $this->vote = new VoteContainer($this->id, $offset);
    }
    return $this->vote;
  }

  function getHearingContainer($offset) {
    if(!isset($this->hearing)) {
      $this->hearing = new HearingContainer($this->id, $offset);
    }
    return $this->hearing;
  }

  function getVideoContainer($offset) {
    if(!isset($this->video)) {
      $this->video = new VideoContainer($this->id, $offset);
    }
    return $this->video;
  }

  function getAmendmentContainer($offset) {
    if(!isset($this->Amendment)) {
      $this->amendment = new AmendmentContainer($this->id, $offset);
    }
    return $this->amendment;
  }
}
