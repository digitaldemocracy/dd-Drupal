<?php

namespace Drupal\dd_bill_alerts;

class Hearing {

  private $date;
  private $committee_number;
  private $committee_name;

  function __construct($date, $committee_number, $committee_name) {
    $this->date = $date;
    $this->committee_number = $committee_number;
    $this->committee_name = $committee_name;
  }

  function __toString() {
    return 'Hearing of ' . $this->date . ', committee: ' . $this->committee_number;
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

}
