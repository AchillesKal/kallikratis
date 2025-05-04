<?php

namespace Kallikratis\Model;

final class RegionalUnit
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        /** @var Municipality[] */
        public array $municipalities = []
    ) {}

    /**
     * @return Municipality[]
     */
    public function getMunicipalities(): array
    {
        return $this->municipalities;
    }
}
