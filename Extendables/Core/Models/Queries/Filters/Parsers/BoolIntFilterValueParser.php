<?php

namespace App\Extendables\Core\Models\Queries\Filters\Parsers;

use App\Extendables\Core\Http\Request\States\QueryString\FilterCondition;
use App\Extendables\Core\Utils\BoolIntValueEnum;

/**
 * For a flag filter (`filter[is_…]=1|0`). A flag is passed as `1` / `0`, the way the responses return flags, never as
 * `true` / `false`: `JsonApiFilterQueryStringState` turns those literals into PHP booleans, which this reads as no
 * value, like any other.
 */
trait BoolIntFilterValueParser
{
    /**
     * The flag, or null when the value is neither `1` nor `0` (no filter).
     */
    private function flag(FilterCondition $filterCondition): ?BoolIntValueEnum
    {
        return match ($filterCondition->condition) {
            (string) BoolIntValueEnum::TRUE->value => BoolIntValueEnum::TRUE,
            (string) BoolIntValueEnum::FALSE->value => BoolIntValueEnum::FALSE,
            default => null,
        };
    }
}
