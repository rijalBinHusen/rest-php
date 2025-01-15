<?php
function findIndexByKeyAndValue($array, $key, $value)
{
    foreach ($array as $index => $item) {
        if (isset($item[$key]) && $item[$key] === $value) {
            return $index;
        }
    }
    return false; // Not found
}
