<?php

namespace Kallikratis\Model;

use Kallikratis\Enum\CommunityType;

final readonly class Community
{
    public function __construct(
        public int $id,
        public string $name,
        public CommunityType $type,
    ) {}
}
