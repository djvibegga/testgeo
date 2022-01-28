<?php

require_once __DIR__ . '/thirdparty_cadastr_api.php';

$inputFile = __DIR__ . '/data/cadastr.csv';

if (($handle = fopen($inputFile, "r")) === false) {
    echo "Unable to read input cadastr file...";
    exit (1);
}


fgetcsv($handle, 0, ","); //skip header line
while (($data = fgetcsv($handle, 0, ",")) !== false) {
    $result = requestCadastrCenter($data[0]);
    if (is_array($result) && count($result) == 2) {
        list($lat, $lng) = $result;
        echo $data[0] . ' -> center (lat: ' . $lat . ', lng: ' . $lng . ")\n";
    } else {
        echo $data[0] . ' -> unable to find center...' . "\n";
    }
}
fclose($handle);