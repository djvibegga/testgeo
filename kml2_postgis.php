<?php

require_once __DIR__ . '/bootstrap.php';

$inputFile = __DIR__ . '/data/geozone.kml';
$kml = simplexml_load_file($inputFile);
$polygon = $kml->Document->Placemark->Polygon->asXML();

global $pgConnect;
if (! $dbconn = $pgConnect()) {
    echo "Unable to connect to database. Please recheck environment...\n";
}

$sql = "SELECT ST_GeomFromKML('" . $polygon . "')";
$result = pg_query($dbconn, $sql);
if ($result) {
    $line = pg_fetch_array($result, 0);
    echo 'PG GEO: ' . $line[0] . "\n";
}

pg_close($dbconn);
