<?php

namespace Kallikratis\Enum;

enum CommunityType: string
{
    case Municipal = 'municipal';  // ΔΗΜ. ΚΟΙΝΟΤ
    case Local = 'local';          // ΤΟΠ. ΚΟΙΝΟΤ

    /**
     * Convert a Greek label to the corresponding CommunityType enum.
     */
    public static function fromGreek(string $label): ?self
    {
        $label = mb_strtoupper(trim($label));

        return match (true) {
            str_starts_with($label, 'ΔΗΜ. ΚΟΙΝΟΤ') => self::Municipal,
            str_starts_with($label, 'ΤΟΠ. ΚΟΙΝΟΤ') => self::Local,
            default => null,
        };
    }
}
