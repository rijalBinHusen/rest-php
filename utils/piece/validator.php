<?php

class Validator
{

  // Methods
  function isYMDDate($yourDate)
  {

    $dt = DateTime::createFromFormat("Y-m-d", $yourDate);
    return $dt !== false && !array_sum($dt::getLastErrors());
  }

  public function check_type($your_object, $whats_to_check)
  {
    foreach ($whats_to_check as $key => $value) {

      $data_to_check = $your_object->$key;

      if (is_null($data_to_check)) return false;
      if ($value == "string") {
        if (!is_string($data_to_check)) return false;
      }
      if ($value == "boolean") {
        if (!is_bool($data_to_check)) return false;
      }
      if ($value == "number") {
        if (!is_numeric($data_to_check)) return false;
      }
    }

    return true;
  }
}
