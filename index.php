<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Καλλικράτης ΟΤΑ Διάρθρωση</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .region {
            background-color: #e3e3e3;
            padding: 10px;
            margin-top: 20px;
            border-radius: 6px;
        }
        summary {
            font-weight: bold;
            cursor: pointer;
        }
        .unit, .municipality, .municipal-unit, .community {
            margin-left: 20px;
            margin-top: 5px;
            background: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .info {
            font-size: 0.9em;
            color: #333;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<h1>Καλλικράτης Ιεραρχία ΟΤΑ</h1>

<?php

require __DIR__.'/vendor/autoload.php';

use Kallikratis\Repository\KallikratisRepository;

function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$repo = new KallikratisRepository();

foreach ($repo->allRegions() as $region) {
    echo "<div class='region'>";
    echo "<details open><summary>Περιφέρεια: " . escape($region->name) . " (ID: {$region->id})</summary>";

    foreach ($region->getRegionalUnits() as $ru) {
        echo "<div class='unit'>";
        echo "<details><summary>Π.Ε.: " . escape($ru->name) . " (ID: {$ru->id})</summary>";

        foreach ($ru->getMunicipalities() as $municipality) {
            echo "<div class='municipality'>";
            echo "<details><summary>Δήμος: " . escape($municipality->name) . " (ID: {$municipality->id})</summary>";

            foreach ($municipality->getMunicipalUnits() as $munUnit) {
                echo "<div class='municipal-unit'>";
                echo "<details><summary>Δημοτική Ενότητα: " . escape($munUnit->name) . " (ID: {$munUnit->id})";
                echo "</summary>";

                foreach ($munUnit->getCommunities() as $community) {
                    echo "<div class='community'>";
                    echo "<strong>" . $community->id. ' - ' . escape($community->name) . " - " . $community->type->value . " </strong><br>";
                    echo "</div>";
                }

                echo "</details></div>";
            }

            echo "</details></div>";
        }

        echo "</details></div>";
    }

    echo "</details></div>";
}
?>

</body>
</html>
