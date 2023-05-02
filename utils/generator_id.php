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
    $yearNow = substr($fullYearNow, 2, 2);
    // week now
    $weekNow = $yourDate2->format("W");
    // year of last id
    $yearLastId = substr($lastId, strlen($baseId), 2); //22
    // week of last id
    $weekLastId = substr($lastId, (strlen($baseId) + 2), 2); //08
    
    if ($weekNow == $weekLastId && $yearNow == $yearLastId) {
        return $baseId . $yearLastId . $weekNow . substr("0000", strlen($increment)) . $increment;
    }
    
    return $baseId . $yearNow . $weekNow . "0000";
}
