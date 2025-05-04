<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = 'resources/kallikraths_kwdikologio1_31_5_11.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$regions = [];

$regionCount = 0;
$regionalUnitCount = 0;
$municipalityCount = 0;
$municipalUnitCount = 0;
$communityCount = 0;

foreach ($sheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    $rowData = [];
    foreach ($cellIterator as $cell) {
        $rowData[] = $cell->getValue();
    }

    if (!is_numeric($rowData[0] ?? null)) {
        continue;
    }

    $communityId       = $rowData[0] ?? null;
    $oldOtaType        = $rowData[1] ?? null;
    $oldOtaName        = $rowData[2] ?? null;
    $communityCode     = $rowData[3] ?? null;
    $communityType     = $rowData[4] ?? null;

    $municipalUnitCode = $rowData[5] ?? null;
    $municipalUnitName = $rowData[6] ?? null;
    $isHQ              = $rowData[7] ?? null;

    $municipalityId    = $rowData[8] ?? null;
    $municipalityName  = $rowData[10] ?? null;

    $regionalUnitId    = $rowData[11] ?? null;
    $regionalUnitName  = $rowData[12] ?? null;

    $regionId          = $rowData[13] ?? null;
    $regionName        = $rowData[14] ?? null;

    if (!$regionId || !$regionName) {
        continue;
    }

    if (!isset($regions[$regionId])) {
        $regions[$regionId] = [
            'id' => $regionId,
            'name' => mb_convert_case($regionName, MB_CASE_TITLE, 'UTF-8'),
            'regional_units' => [],
        ];
        $regionCount++;
    }

    if ($regionalUnitId && $regionalUnitName) {
        $ruId = str_pad((string)$regionalUnitId, 4, '0', STR_PAD_LEFT);

        if (!isset($regions[$regionId]['regional_units'][$ruId])) {
            $regions[$regionId]['regional_units'][$ruId] = [
                'id' => $ruId,
                'name' => mb_convert_case($regionalUnitName, MB_CASE_TITLE, 'UTF-8'),
                'municipalities' => [],
            ];
            $regionalUnitCount++;
        }

        if ($municipalityId && $municipalityName) {
            $munId = (string)$municipalityId;

            if (!isset($regions[$regionId]['regional_units'][$ruId]['municipalities'][$munId])) {
                $regions[$regionId]['regional_units'][$ruId]['municipalities'][$munId] = [
                    'id' => $munId,
                    'name' => mb_convert_case($municipalityName, MB_CASE_TITLE, 'UTF-8'),
                    'municipal_units' => [],
                ];
                $municipalityCount++;
            }

            if ($municipalUnitCode && $municipalUnitName) {
                $munUnitId = (string)$municipalUnitCode;

                if (!isset($regions[$regionId]['regional_units'][$ruId]['municipalities'][$munId]['municipal_units'][$munUnitId])) {
                    $regions[$regionId]['regional_units'][$ruId]['municipalities'][$munId]['municipal_units'][$munUnitId] = [
                        'id' => $munUnitId,
                        'name' => mb_convert_case($municipalUnitName, MB_CASE_TITLE, 'UTF-8'),
                        'is_hq' => ($isHQ === 'ΝΑΙ'),
                        'communities' => [],
                    ];
                    $municipalUnitCount++;
                }

                if ($communityId && $oldOtaName) {
                    $comId = (string) $communityId;
                    $regions[$regionId]['regional_units'][$ruId]['municipalities'][$munId]['municipal_units'][$munUnitId]['communities'][$communityCode] = [
                        'id' => $comId,
                        'name' => mb_convert_case($oldOtaName, MB_CASE_TITLE, 'UTF-8'),
                        'old_ota_type' => $oldOtaType,
                        'community_code' => $communityCode,
                        'community_type' => $communityType,
                    ];
                    $communityCount++;
                }
            }
        }
    }
}

// Normalize to indexed arrays
foreach ($regions as &$region) {
    foreach ($region['regional_units'] as &$ru) {
        foreach ($ru['municipalities'] as &$municipality) {
            foreach ($municipality['municipal_units'] as &$munUnit) {
                $munUnit['communities'] = array_values($munUnit['communities']);
            }
            $municipality['municipal_units'] = array_values($municipality['municipal_units']);
        }
        $ru['municipalities'] = array_values($ru['municipalities']);
    }
    $region['regional_units'] = array_values($region['regional_units']);
}

file_put_contents('resources/kallikratis.json', json_encode(array_values($regions), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "✔ Extracted:\n";
echo " - $regionCount regions\n";
echo " - $regionalUnitCount regional units\n";
echo " - $municipalityCount municipalities\n";
echo " - $municipalUnitCount municipal units\n";
echo " - $communityCount local communities\n";
