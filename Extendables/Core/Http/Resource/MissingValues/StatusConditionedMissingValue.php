<?php

namespace App\Extendables\Core\Http\Resource\MissingValues;

use Illuminate\Http\Resources\MissingValue;

/**
 * A key left out of the response because the record's state does not meet the display condition of its block (for
 * example, a block that exists only before or after a given status). Use it instead of a bare {@see MissingValue}, so the
 * reason for the omission is visible where the key is built. Name each such key in the resource's PHPDoc.
 */
class StatusConditionedMissingValue extends MissingValue {}
