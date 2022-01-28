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
    exit (2);
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
    $absPath = $inputDir . '/' . $entry;
    $kml = simplexml_load_file($absPath);
    $number = pathinfo($entry, PATHINFO_FILENAME);
    $polygon = $kml->Document->Placemark->Polygon->asXML();
    $result = pg_execute($dbconn, "insertcadastr", [$number, $polygon]);
    if ($result === false) {
        echo 'Unable to import cadast: ' . $absPath . "\n";
    } else {
        echo 'Successfully imported cadast: ' . $absPath . "\n";
    }
}

pg_close($dbconn);
closedir($handle);