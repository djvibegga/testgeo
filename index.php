<?php

require_once __DIR__ . '/thirdparty_cadastr_api.php';
require_once __DIR__ . '/bootstrap.php';

function sendResponse($json = ['success' => true], $httpCode = 200, $exit = true) {
    header('Content-type: application/json');
    echo json_encode($json);
    http_response_code($httpCode);
    $exit && exit;
}

$requestUri = $_SERVER['REQUEST_URI'];
if (! preg_match('/^\/api(.*)$/si', $requestUri)) { //api call
    sendResponse(['success' => false], 404);
}

if (empty($_REQUEST['cadastr'])) {
    sendResponse(['success' => false], 400);
}

global $pgConnect;
if (! $dbconn = $pgConnect()) {
    sendResponse(['success' => false], 500);
}

$cadastr = $_REQUEST['cadastr'];
$center = requestCadastrCenter($cadastr);

if (! is_array($center) || count($center) != 2) { //unable to find out center
    sendResponse(['success' => false, 'msg' => 'Cadastr center has not found.'], 404);
}

$sql = 'SELECT ST_AsGeoJSON("border") FROM "cadastr" WHERE "number" = $1';
pg_prepare($dbconn, "getBoders", $sql);
$result = pg_execute($dbconn, "getBoders", [$cadastr]);
if (! $result) {
    sendResponse(['success' => false, 'msg' => 'Cadastr 1 has not found.'], 404);
}

if ( ($line = pg_fetch_row($result)) === false) {
    sendResponse(['success' => false, 'msg' => 'Cadastr 2 has not found.'], 404);
}

sendResponse([
    'success' => true,
    'data' => [
        'geojson' => json_decode($line[0], true),
        'center' => ['lat' => $center[0], 'lng' => $center[1]]
    ]
], 200);