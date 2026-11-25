<?php

namespace App\Extendables\Core\Utils;

enum BoolIntValueEnum: int
{
    case FALSE = 0;
    case TRUE = 1;

    public static function fromBool(bool $value): self
    {
        return $value ? self::TRUE : self::FALSE;
    }
}
