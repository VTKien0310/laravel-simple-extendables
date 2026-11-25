<?php

namespace App\Extendables\Core\Http\Resource\MissingValues;

use Illuminate\Http\Resources\MissingValue;

/**
 * A key left out of the response because the actor lacks the permission that gates it. Use it instead of a bare
 * {@see MissingValue}, so the reason for the omission is visible where the key is built. Name each such key, with its
 * permission, in the resource's PHPDoc.
 */
class PermissionGatedMissingValue extends MissingValue {}
