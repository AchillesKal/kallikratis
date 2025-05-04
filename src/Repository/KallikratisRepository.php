<?php

declare(strict_types=1);

namespace Kallikratis\Repository;

use Kallikratis\Enum\CommunityType;
use Kallikratis\Model\Community;
use Kallikratis\Model\Municipality;
use Kallikratis\Model\MunicipalUnit;
use Kallikratis\Model\Region;
use Kallikratis\Model\RegionalUnit;

final class KallikratisRepository
{
    /** @var Region[] */
    private array $regions = [];

    public function __construct()
    {
        $dataPath = __DIR__ . '/../../resources/kallikratis.json';

        $json = file_get_contents($dataPath);
        if ($json === false) {
            throw new \RuntimeException("Cannot read Kallikratis JSON file: $dataPath");
        }

        $data = json_decode($json, true);
        if ($data === null) {
            throw new \RuntimeException("Invalid JSON in: $dataPath");
        }

        $this->regions = $this->hydrateRegions($data);
    }

    private function hydrateRegions(array $regionsData): array
    {
        $regions = [];

        foreach ($regionsData as $regionArr) {
            $regionalUnits = [];
            foreach ($regionArr['regional_units'] ?? [] as $ruArr) {
                $municipalities = [];
                foreach ($ruArr['municipalities'] ?? [] as $mArr) {
                    $municipalUnits = [];
                    foreach ($mArr['municipal_units'] ?? [] as $muArr) {
                        $communities = [];
                        foreach ($muArr['communities'] ?? [] as $cArr) {
                            $type = CommunityType::fromGreek($cArr['community_type']);
                            $communities[] = new Community((int) $cArr['community_code'], $cArr['name'], $type);
                        }
                        $municipalUnits[] = new MunicipalUnit((int)$muArr['id'], $muArr['name'], $communities);
                    }
                    $municipalities[] = new Municipality((int)$mArr['id'], $mArr['name'], $municipalUnits);
                }
                $regionalUnits[] = new RegionalUnit((int)$ruArr['id'], $ruArr['name'], $municipalities);
            }
            $regions[] = new Region((int) $regionArr['id'], $regionArr['name'], $regionalUnits);
        }

        return $regions;
    }

    /**
     * @return Region[]
     */
    public function allRegions(): array
    {
        return $this->regions;
    }

    /**
     * @param int $id
     * @return Region|null
     */
    public function findRegionById(int $id): ?Region
    {
        foreach ($this->regions as $region) {
            if ($region->id === $id) {
                return $region;
            }
        }
        return null;
    }
}
