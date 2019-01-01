<?php

namespace Drupal\dd_bill_alerts;

use Drupal\node\Entity\Node;
use Drupal\dd_base\DdBase;
use Drupal\dd_bill_alerts\Bill;

class BillAlert {

  private $title; // String
  private $bill; // Bill object
  private $vote; // Bool
  private $hearing; // Bool
  private $video; // Bool
  private $amendment; // Bool

  function __construct($title, $bill, $vote, $hearing, $video, $amendment) {
    if(!($bill instanceof Bill)) { 
      throw new Exception('Invalid input in BillAlert constructor. Bill is not an instance of Bill.'); 
    }
    if(is_bool($vote) && is_bool($hearing) && is_bool($video) && is_bool($amendment)) {
      throw new Exception('Invalid input to BillAlert constructor. Not a boolean.'); 
    }
  
    $this->title = $title;
    $this->bill = $bill;
    $this->vote = $vote;
    $this->hearing = $hearing;
    $this->video = $video;
    $this->amendment = $amendment;
  }

  // Get dd_bill_alert nodes from Drupal
  static function get_bill_alert_nodes() {
    $query = \Drupal::entityQuery('node')->condition('type','dd_bill_alert');
    $nids = $query->execute();
    return Node::loadMultiple($nids); /*array of dd bill alert objects */
  }

  // Performs checks on validity of the alert.
  // Returns true if valid, false if not.
  // Throws exceptions for things that should never happen,
  // such as if the front end should be preventing it.
  static function validate_bill_alert_node($alert) {
    
    // First check that it is a dd_bill_alert
    if($alert->type->getValue()[0]['target_id'] != 'dd_bill_alert') {
      throw new Exception('Attempting to validate non dd_bill_alert.');
    }

    // Check if user has permission for bill alerts
    $uid = (int) $alert->get('uid')->first()->target_id;
    if (!\Drupal\user\Entity\User::load($uid)->hasPermission('create dd_bill_alert content')) {
      return FALSE;
    }

    // Check that at least one type is checked
    if(!$alert->field_receive_alerts_for_votes->value &&
     !$alert->field_receive_alerts_for_schedul->value &&
     !$alert->field_receive_alerts_for_videos->value &&
     !$alert->field_alert_me_when_bill_is_amen->value) {
      return FALSE;
      //throw new Exception('Validation failure. No types of alerts selected.');
    }

    return TRUE;
  }

  // Returns true or false based on if the search found results
  // Note that this function doesn't short circuit checking each type
  // because the just-in-time results populating in Bill.php means
  // that the searches for results will happen during this function
  static function hasResults($alert, $offset) {
    $has_results = FALSE;
    if($alert->getVote() && $alert->getBill()->getVoteContainer($offset)->hasResult()){
      $has_results = TRUE;
    }
    if($alert->getHearing() && $alert->getBill()->getHearingContainer($offset)->hasResult()){
      $has_results = TRUE;
    }
    if($alert->getVideo() && $alert->getBill()->getVideoContainer($offset)->hasResult()){
      $has_results = TRUE;
    }
    if($alert->getAmendment() && $alert->getBill()->getAmendmentContainer($offset)->hasResult()){
      $has_results = TRUE;
    }
    return $has_results;
  }

  function getBill() {
    return $this->bill;
  }

  function getTitle() {
    return $this->title;
  }

  function getVote() {
    return $this->vote;
  }
  function setVote() {
    $this->vote = TRUE;
  }

  function getHearing() {
    return $this->hearing;
  }
  function setHearing() {
    $this->hearing = TRUE;
  }

  function getVideo() {
    return $this->video;
  }
  function setVideo() {
    $this->video = TRUE;
  }

  function getAmendment() {
    return $this->amendment;
  }
  function setAmendment() {
    $this->amendment = TRUE;
  }








}
