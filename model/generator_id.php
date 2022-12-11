<?php
function generateId($lastId)
{
    // base id, warehouse = war
    $baseId = substr($lastId, 0, 3);
    // ambil 4 angka dibelakang war22050000 jadinya 0000
    $getNumber = substr($lastId, 7);
    // increment, tambahkan angka 1
    $increment = strval(floatval($getNumber) + 1);
    // tahun sekarang penuh
    $fullYearNow = date("Y") . "";
    // week sekarang
    $weekNow = date("W") < 9 ? "0" . date("W") : date("W");
    // tahun dari last id
    $yearLastId = substr($lastId, 3, 2); //21
    // week dari last id
    $weekLastId = substr($lastId, 5, 2); //08
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
