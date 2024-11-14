<?php


Flight::route('GET /google/sign', function () {
    include('signIn.php');
});

Flight::route('GET /google/sheet', function () {
    // 
    // protected $paymentSpreadsheetId = PAYMENT_SPREADSHEET_ID;
    include('spreadsheet.php');
});
