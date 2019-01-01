<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_bill_alerts\Vote;
use Drupal\dd_bill\Entity\DdBillVoteSummary;
use Drupal\dd_bill\Entity\DdMotion;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_base\DdBase;

class VoteContainer {

  private $votes = [];
  private $has_result = FALSE; // Bool

  function __construct($bid, $offset) {
    $since_date = $offset;

    $field_values = [
      ['field' => 'bid', 'value' => $bid],
      ['field' => 'VoteDate_ts', 'value' => strtotime("-7 days"), 'op' => '>='],
      ['field' => 'lastTouched_ts', 'value' => strtotime($since_date), 'op' => '>='],
    ];
    $votes = DdBillVoteSummary::loadByFields($field_values);
    foreach($votes as $vote) {

      // Get the vote details
      $date = date('m/d/Y', $vote->VoteDate_ts->value);
      $result = $vote->result->value;
      $ayes = $vote->ayes->value;
      $naes = $vote->naes->value;
      $abstains = $vote->abstain->value;

      // Get the motion
      if (DdBase::getCurrentState() == 'CA') {
        $mid = $vote->getMid()[0]['target_id']; 
        $motion = DdMotion::load($mid);
        $motion_text = $motion->getText();
      } else {
        $motion_text = '';
      }

      // Get the committee
      $cid = $vote->getCid()[0]['target_id'];
      $committee = DdCommittee::load($cid);

      // Get the committee name and link id
      $committee_name = $committee->getName();
      $committee_number = $committee->getCommitteeNameId();

      $this->votes[] = new Vote($date, $committee_number, $committee_name, $result, $ayes, $naes, $abstains, $motion_text);
      $this->has_result = TRUE;
    }
  }

  function getVotes() {
    return $this->votes;
  }

  function hasResult() {
    return $this->has_result;
  }

}

