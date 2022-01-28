<?php

$inputFile = __DIR__ . '/data/cadastr.csv';
$outputDir = __DIR__ . '/data/cadastr_samples';

if (($handle = fopen($inputFile, "r")) === false) {
    echo "Unable to read input cadastr file...";
    exit (1);
}

$polygonTemplate = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
<Document>
    <name>Untitled Polygon.kml</name>
    <Placemark>
        <name>Untitled Polygon</name>
        <Polygon>
            <outerBoundaryIs>
                <LinearRing>
                    <coordinates>
                        {coordinates}
                    </coordinates>
                </LinearRing>
            </outerBoundaryIs>
        </Polygon>
    </Placemark>
</Document>
</kml>
XML;

function randomPolygon($fromLat, $toLat, $fromLng, $toLng, $numberPoints, $divideBy = 100) {
    $ret = [];
    for ($i = 0; $i < $numberPoints; ++$i) {
        $lat = mt_rand($fromLat, $toLat);
        $lng = mt_rand($fromLng, $toLng);
        $ret[] = '' . number_format($lat / $divideBy, 7) . ',' . number_format($lng / $divideBy, 7) . ',0';
    }
    return $ret;
}

fgetcsv($handle, 0, ","); //skip header line
while (($data = fgetcsv($handle, 0, ",")) !== false) {
    $amountPolyPoints = rand(4, 10);
    $polygonCoords = randomPolygon(3392, 4500, 4080, 5085, $amountPolyPoints);
    $polygon = strtr($polygonTemplate, [
        '{coordinates}' => implode(' ', $polygonCoords)
    ]);
    file_put_contents($outputDir . '/' . $data[0] . '.kml', $polygon);
    echo $outputDir . '/' . $data[0] . ".kml created...\n";
}
fclose($handle);
