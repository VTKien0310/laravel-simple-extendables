<?php

namespace App\Extendables\Core\Models\Queries\Filters\Parsers;

use App\Extendables\Core\Http\Request\States\QueryString\FilterCondition;

/**
 * For a free-text keyword filter (`filter[keyword]=…`) matched as typed. `JsonApiFilterQueryStringState` splits a
 * value on `,` and turns the literals `true` / `false` / `null` into PHP values; this undoes both, trims the keyword
 * and keeps its first `KEYWORD_MAX_LENGTH` characters.
 */
trait KeywordFilterValueParser
{
    private const int KEYWORD_MAX_LENGTH = 100;

    /**
     * The keyword as typed, or null when it is blank (no filter).
     */
    private function keyword(FilterCondition $filterCondition): ?string
    {
        $condition = $filterCondition->condition;

        $keyword = match (true) {
            is_array($condition) => implode(',', $condition),
            is_bool($condition) => $condition ? 'true' : 'false',
            is_null($condition) => 'null',
            default => $condition,
        };

        $keyword = mb_substr(mb_trim($keyword), 0, self::KEYWORD_MAX_LENGTH);

        return $keyword === '' ? null : $keyword;
    }

    /**
     * A `LIKE` pattern matching any value that contains `$keyword`, its `\`, `%` and `_` matched literally.
     */
    private function containsPattern(string $keyword): string
    {
        return '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $keyword).'%';
    }
}
