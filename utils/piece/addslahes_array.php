<?php

function adslashes_array($array) {
  $new_array = [];
  foreach ($array as $value) {
    $new_value = addslashes($value);
    $new_array[] = $new_value;
  }
  return $new_array;
}

