<?php

function generateId($lastId) {
    $date = date("Y-m-d");
    return generateIdWithCustomDate($lastId, $date);
}

function generateIdWithCustomDate($lastId, $yourDate)
{
    $yourDate2 =  date_create($yourDate);
    // get uniquee id, the 8 last string, SUPERVISOR_22030001 become SUPERVISOR_
    $baseId = substr($lastId, 0, -8);
    // get uniquee number, the last 4 string, war22050000 become 0000
    $getNumber = substr($lastId, -4);
    // increment uniquee number by 1
    $increment = strval(floatval($getNumber) + 1);
    // full year
    $fullYearNow = $yourDate2->format("Y") . "";
    // week now
    $weekNow = $yourDate2->format("W");
    // year of last id
    $yearLastId = substr($lastId, strlen($baseId), (strlen($baseId) + 2)); //22
    // week of last id
    $weekLastId = substr($lastId, (strlen($baseId) + 2), (strlen($baseId) + 4)); //08
    //if the week same
    if ($weekNow === $weekLastId) {
        $baseId = $baseId . $yearLastId . $weekLastId;
    }
    //if the week not same
    else {
        // if the week 9 change to 09
        $baseId = $baseId . substr($fullYearNow, 2, 2) . $weekNow;
        $increment = "0";
    }
    // //0000
    $result = $baseId . substr("0000", strlen($increment)) .  $increment;

    return $result;
}
