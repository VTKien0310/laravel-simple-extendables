<?php

namespace App\Extendables\Core\Models\Queries\Filters\Parsers;

use App\Extendables\Core\Http\Request\States\QueryString\FilterCondition;
use Carbon\CarbonImmutable;
use DateTimeImmutable;

/**
 * For a filter taking one instant (`filter[x_from]=2026-11-04T15:00:00Z`). A screen that shows a calendar day sends that
 * day's start or end as a UTC date-time, so the server never decides which time zone the day belongs to.
 */
trait DateTimeFilterValueParser
{
    /**
     * The date-time formats a request may send: ISO 8601 with an explicit zone (`Z` or an offset), with or without a
     * fraction of a second, e.g. `2026-11-04T15:00:00Z` or `2026-11-05T14:59:59.999Z`. A value without a zone is not
     * one of them: its instant would depend on the server's time zone. A class using this trait reads it as `self::`,
     * e.g. for the `date_format` rule of a request body carrying the same condition.
     *
     * Each layout comes with `p` and `P`: Laravel's `date_format` formats the parsed value back and compares it with the
     * input, and only `p` writes UTC as `Z`, only `P` as `+00:00`.
     *
     * @var list<string>
     */
    public const array REQUEST_DATE_TIME_FORMATS = [
        'Y-m-d\TH:i:sp', 'Y-m-d\TH:i:s.vp', 'Y-m-d\TH:i:s.up',
        'Y-m-d\TH:i:sP', 'Y-m-d\TH:i:s.vP', 'Y-m-d\TH:i:s.uP',
    ];

    /**
     * The instant in UTC when the condition is one of {@see self::REQUEST_DATE_TIME_FORMATS}, else null. A date that
     * does not exist (`2026-02-30T00:00:00Z`) is null too.
     */
    private function dateTime(FilterCondition $filterCondition): ?CarbonImmutable
    {
        $value = $filterCondition->condition;

        if (! is_string($value)) {
            return null;
        }

        foreach (self::REQUEST_DATE_TIME_FORMATS as $format) {
            $parsed = DateTimeImmutable::createFromFormat('!'.$format, $value);

            // An overflowing date parses, but leaves a warning behind.
            if ($parsed !== false && DateTimeImmutable::getLastErrors() === false) {
                return CarbonImmutable::instance($parsed)->utc();
            }
        }

        return null;
    }
}
