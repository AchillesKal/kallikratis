<?php

namespace Kallikratis\Model;

final class Municipality
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        /** @var MunicipalUnit[] */
        public array $municipalUnits = []
    ) {}

    /**
     * @return MunicipalUnit[]
     */
    public function getMunicipalUnits(): array
    {
        return $this->municipalUnits;
    }
}
