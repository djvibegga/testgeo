<?php

require_once __DIR__ . '/bootstrap.php';

$inputDir = __DIR__ . '/data/cadastr_samples';
if (! ($handle = opendir($inputDir))) {
    echo "Unable to read input cadastr samples...";
    exit (1);
}

global $pgConnect;
if (! $dbconn = $pgConnect()) {
    echo "Unable to connect to database. Please recheck environment...\n";
}

$sql = 'INSERT INTO "cadastr" ("number", "border") VALUES ($1, ST_GeomFromKML($2))';
if (! pg_prepare($dbconn, "insertcadastr", $sql)) {
    echo "Unable to prepare import cadastrs query...";
    exit (2);
}

while (false !== ($entry = readdir($handle))) {
    if ($entry == '.' || $entry == '..') {
        continue;
    }
    $kml = simplexml_load_file($inputDir . '/' . $entry);
    $number = pathinfo($entry, PATHINFO_FILENAME);
    $polygon = $kml->Document->Placemark->Polygon->asXML();
    $result = pg_execute($dbconn, "insertcadastr", [$number, $polygon]);
    if ($result === false) {
        echo 'Unable to import cadast: ' . $entry . "\n";
    } else {
        echo 'Successfully imported cadast: ' . $entry . "\n";
    }
}

pg_close($dbconn);
closedir($handle);