<?php

namespace Drupal\dd_bill_alerts;

class Vote {

  private $date;
  private $committee_number;
  private $committee_name;
  private $result;
  private $ayes;
  private $naes;
  private $abstains;
  private $motion;

  function __construct($date, $committee_number, $committee_name, $result, $ayes, $naes, $abstains, $motion) {
    $this->date = $date;
    $this->committee_number = $committee_number;
    $this->committee_name = $committee_name;
    $this->result = $result;
    $this->ayes = $ayes;
    $this->naes = $naes;
    $this->abstains = $abstains;
    $this->motion = $motion;
  }

  function __toString() {
    return 'Vote of ' . $this->date . ', committee: ' . $this->committee_number . ', result: ' . $this->result . ', ayes: ' . $this->ayes . ', naes: ' . $this->naes . ', abstains: ' . $this->abstains . ', motion: ' . $this->motion; 
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
  function getResult() {
    return $this->result;
  }
  function getAyes() {
    return $this->ayes;
  }
  function getNaes() {
    return $this->naes;
  }
  function getAbstains() {
    return $this->abstains;
  }
  function getMotion() {
    return $this->motion;
  }

}
