<?php

namespace Kallikratis\Model;

final class Region
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        /** @var RegionalUnit[] */
        public array $regionalUnits = []
    ) {}

    /**
     * @return RegionalUnit[]
     */
    public function getRegionalUnits(): array
    {
        return $this->regionalUnits;
    }
}
