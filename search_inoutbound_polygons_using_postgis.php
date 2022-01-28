<?php

require_once __DIR__ . '/bootstrap.php';

$inputFile = __DIR__ . '/data/geozone.kml';
$kml = simplexml_load_file($inputFile);
$inputPolygon = $kml->Document->Placemark->Polygon->asXML();

global $pgConnect;
if (! $dbconn = $pgConnect()) {
    echo "Unable to connect to database. Please recheck environment...\n";
}

$inboundSql = 'SELECT "number" FROM "cadastr" WHERE ST_Contains(ST_GeomFromKML($1), "border")';
pg_prepare($dbconn, "inboundCadastrs", $inboundSql);
$result = pg_execute($dbconn, "inboundCadastrs", [$inputPolygon]);
if ($result) {
    echo 'Cadastrs inside polygon found amount: ' . pg_num_rows($result) . "\n";
    echo "Results list:\n";
    while (($line = pg_fetch_row($result)) !== false) {
        echo $line[0] . "\n";
    }
}

$outboundSql = 'SELECT "number" FROM "cadastr" WHERE NOT(ST_Contains(ST_GeomFromKML($1), "border"))';
pg_prepare($dbconn, "outboundCadastrs", $outboundSql);
$result = pg_execute($dbconn, "outboundCadastrs", [$inputPolygon]);
if ($result) {
    echo 'Cadastrs outside polygon found amount: ' . pg_num_rows($result) . "\n";
    echo "Results list:\n";
    while (($line = pg_fetch_row($result)) !== false) {
        echo $line[0] . "\n";
    }
}

pg_close($dbconn);