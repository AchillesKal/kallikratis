<?php

namespace Kallikratis\Model;

final class MunicipalUnit
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        /** @var Community[] */
        public array $communities = []
    ) {}

    /**
     * @return Community[]
     */
    public function getCommunities(): array
    {
        return $this->communities;
    }
}
