<?php

class Validator {

  // Methods
  function isYMDDate($yourDate) {
    
    $dt = DateTime::createFromFormat("Y-m-d", $yourDate);
    return $dt !== false && !array_sum($dt::getLastErrors());
  }
}