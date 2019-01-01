<?php

namespace Drupal\dd_bill_alerts;

class Amendment {

  private $date;
  private $bill_state;

  function __construct($date, $bill_state) {
    $this->date = $date;
    $this->bill_state = $bill_state;
  }

  function __toString() {
    return 'Amendment of ' . $this->date . ', bill state: ' . $this->bill_state;
  }

  function getDate() {
    return $this->date;
  }

  function getBillState() {
    return $this->bill_state;
  }

}
