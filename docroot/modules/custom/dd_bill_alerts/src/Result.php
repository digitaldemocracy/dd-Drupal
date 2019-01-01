<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_bill_alerts\Entity\DdBillAlertHistory;

class Result {

  public $alert_title;
  public $bill_name;
  public $bill_link;
  public $bid;
  public $votes = []; 
  public $videos = []; 
  public $hearings = [];
  public $amendments = [];  
  private $has_alerts; 

  function __construct($alert, $user_id, $bills, $offset) {
    $this->alert_title = $alert->getTitle();
    $this->bill_name = $alert->getBill()->getName();
    $this->bill_link = $alert->getBill()->getLink();
    $this->bid = $alert->getBill()->getId();
    $this->has_alerts = False;

    // If the user wanted vote alerts for this bill
    if($alert->getVote()) {

      // Get the container of votes and loop through them
      $container = $bills[$this->bid]->getVoteContainer($offset);
      foreach($container->getVotes() as $vote) {

        // If the vote has not already been stored, add it
        if(!$this->stored($user_id, $vote)) {
          $this->votes[] = clone $vote;
          $this->store($user_id, $vote);
          $this->has_alerts = True;
        }
      }
    }

    // If the user wanted video alerts for this bill
    if($alert->getVideo()) {

      // Get the container of videos and loop through them
      $container = $bills[$this->bid]->getVideoContainer($offset);
      foreach($container->getVideos() as $video) {

        // If the video has not already been stored, add it
        if(!$this->stored($user_id, $video)) {
          $this->videos[] = clone $video;
          $this->store($user_id, $video);
          $this->has_alerts = True;
        }
      }
    }

    // If the user wanted hearing alerts for this bill
    if($alert->getHearing()) {

      // Get the container of hearings and loop through them
      $container = $bills[$this->bid]->getHearingContainer($offset);
      foreach($container->getHearings() as $hearing) {

        // If the hearing has not already been stored, add it
        if(!$this->stored($user_id, $hearing)) {
          $this->hearings[] = clone $hearing;
          $this->store($user_id, $hearing);
          $this->has_alerts = True;
        }
      }
    }

    // If the user wanted amendment alerts for this bill
    if($alert->getAmendment()) {

      // Get the container of amendments and loop through them
      $container = $bills[$this->bid]->getAmendmentContainer($offset);
      foreach($container->getAmendmentss() as $amendment) {

        // If the amendment has not already been stored, add it
        if(!$this->stored($user_id, $amendment)) {
          $this->amendments[] = clone $amendment;
          $this->store($user_id, $amendment);
          $this->has_alerts = True;
        }
      }
    }
  }

  function hasAlerts() {
    return $this->has_alerts;
  }

  function store($user_id, $obj) {
/*$nodes = DdBillAlertHistory::loadByFields([]);
foreach($nodes as $node) {
  $node->delete();
}*/

    $history = DdBillAlertHistory::create(
     array(
        'name' => substr(
          'uid_' . $user_id . ' ' . $this->bid . ' ' . strval($obj),0,50
        ),
     ));
    $history->field_user_id->setValue($user_id); 
    $history->field_bill_id->setValue($this->bid); 
    $history->field_result->setValue(strval($obj)); 
    $history->save();
  }

  function stored($user_id, $obj) {
    
      $field_values = [
        ['field' => 'field_user_id', 'value' => $user_id],
        ['field' => 'field_bill_id', 'value' => $this->bid],
        ['field' => 'field_result', 'value' => strval($obj)], 
      ];
      $count = sizeof(DdBillAlertHistory::loadByFields($field_values));
      if ($count == 0) {
        return FALSE;
      } 
      elseif ($count == 1) {
        return TRUE;
      } else { // Should never happen
        throw new Exception('Invalid alert history result. Data error.');
      }
  }

}
