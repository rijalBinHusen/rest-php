<?php

use Google\Client;
use Google\Service\Sheets;

class Google_sheet_operation
{

    protected $client;
    protected $service;

    function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(__DIR__ . '/../../google-config.json');
        $this->client->setApplicationName('Spreadsheet');
        $this->client->setScopes(Google_Service_Sheets::SPREADSHEETS);

        $this->service = new Sheets($this->client);
    }

    function append_data_to_spreadsheet($spreadsheetId, $range, $values)
    {
        // checck $spreadsheetId, $$range, values
        if (!$spreadsheetId) return false;
        if (!$range) return false;
        if (!$values) return false;
        if (!is_array($values) || !is_array($values[0])) return false;
        // $values must be array = [
        //     [
        //         'A1',
        //         'B1',
        //         'C1'
        //     ]
        // ];
        // check value, must 2D array

        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        $result = $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);

        if ($result) return true;
        else return false;
    }
}
