<?php

class Validator
{

  // Methods
  function isYMDDate($dateString)
  {
    // DateTime::createFromFormat returns a DateTime object on success,
    // or false on failure. It also sets errors which can be retrieved.
    $date = DateTime::createFromFormat('Y-m-d', $dateString);

    // Check if the creation was successful AND if the format matches exactly.
    // The second check is important because createFromFormat can sometimes
    // parse partial matches or unrelated parts if the string is longer.
    // For example, '2023-10-05 extra text' might still return a DateTime object.
    return $date && $date->format('Y-m-d') === $dateString;
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
      if ($value == "YMDate") {
        if (!$this->isYMDDate($data_to_check)) return false;
      }
    }

    return true;
  }
}
