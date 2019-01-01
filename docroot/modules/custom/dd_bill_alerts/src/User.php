<?php

namespace Drupal\dd_bill_alerts;

use Drupal\dd_bill_alerts\Result;

class User {

  private $name; // String
  private $id; // Int
  private $email; // String
  private $alerts = []; // Array of BillAlert objects
  private $results = []; // Array of Results objects

  // Constructor is passed user id and uses it to get corresponding email and username
  function __construct($uid) {
    $this->id = $uid;
    $this->email = \Drupal\user\Entity\User::load($uid)->getEmail();
    $this->name = \Drupal\user\Entity\User::load($uid)->getUsername();
  }

  // If correctly passed a BillAlert, then add it to the array, else error
  // Assumes the BillAlert is already validated
  function add_alert($alert) {
    if($alert instanceof BillAlert) {
      $this->alerts[] = $alert;
    }
    else {
      throw new Exception('Invalid input to add_alert in User.');
    }
  }

  // Populate the results array with results specific to this user
  function fill_results($bills, $offset) { 
    // For each alert call the Result constructor with necessary info.
    foreach ($this->alerts as $alert) {
      $result = new Result($alert, $this->id, $bills, $offset);
      if ($result->hasAlerts()) {
        $this->results[] = $result;
      }
    }
  }

  function getName() {
    return $this->name;
  }

  function getId() {
    return $this->id;
  }

  function getEmail() {
    return $this->email;
  }

  function getAlerts() {
    return $this->alerts;
  }
  function getResults() {
    return $this->results;
  }
  function hasResults() {
    return count($this->results);
  }
}
