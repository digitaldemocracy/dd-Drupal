<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_hearing\Entity\DdHearing;
use Drupal\dd_video\Entity\DdVideo;
use Drupal\dd_bill\Entity\DdBillDiscussion;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_bill_alerts\Video;

class VideoContainer {

  private $videos = [];
  private $has_result = FALSE; // Bool

  function __construct($bid, $offset) {
    $since_date = $offset;

    // Load any bill discussions for that hearing which discuss the bill
    $field_values = [
      ['field' => 'bid', 'value' => $bid],
      ['field' => 'lastTouched_ts', 'value' => strtotime($since_date), 'op' => '>='],
    ];
    $discussions = DdBillDiscussion::loadByFields($field_values);

    // Loop through each discussion
    foreach($discussions as $discussion) {

      // Get the corresponding video id and load that video
      $vid = $discussion->getStartVideo()[0]['target_id'];
      $hid = $discussion->getHid()[0]['target_id'];
      $hearing = DdHearing::load($hid);
      if (!$hearing) {
        continue;
      }
      $video = DdVideo::load($vid);
      if (!$video) {
        continue;
      }
      // Get the start time and file id
      $start_time = $discussion->getStartTime();
      $file_id = $video->getFileId();
      $this->has_result = TRUE;

      // Get the committee information
      $cn_id = $hearing->getCommitteeNameIds();
      $name = DdCommittee::loadCommitteeByNameId($cn_id)->getName();
      $date = date('m/d/Y', $hearing->getDate());

      // Put the information in a Video object and put it in the array
      $this->videos[] = new Video($date, $cn_id, $name, $hid, $start_time, $file_id);
    }
  }

  function getVideos() {
    return $this->videos;
  }

  function hasResult() {
    return $this->has_result;
  }

}
