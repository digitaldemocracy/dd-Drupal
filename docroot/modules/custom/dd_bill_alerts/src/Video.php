<?php

namespace Drupal\dd_bill_alerts;

class Video {

  private $date;
  private $committee_number;
  private $committee_name;
  private $hearing_id;
  private $start_time;
  private $file_id;

  function __construct($date, $committee_number, $committee_name, $hearing_id, $start_time, $file_id) {
    $this->date = $date;
    $this->committee_number = $committee_number;
    $this->committee_name = $committee_name;
    $this->hearing_id = $hearing_id;
    $this->start_time = $start_time;
    $this->file_id = $file_id;
  }

  function __toString() {
    return 'Video of ' . $this->date . ', committee: ' . $this->committee_number . ', hearing id: ' . $this->hearing_id . ', start time: ' . $this->start_time . ', file id: ' . $this->file_id;
  }

  function getDate() {
    return $this->date;
  }

  function getCommitteeNumber() {
    return $this->committee_number;
  }
  function getCommitteeName() {
    return $this->committee_name;
  }
  function getHearingId() {
    return $this->hearing_id;
  }
  function getStartTime() {
    return $this->start_time;
  }
  function getFileId() {
    return $this->file_id;
  }

}
