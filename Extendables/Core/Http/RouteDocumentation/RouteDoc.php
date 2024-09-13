<?php

namespace App\Extendables\Core\Http\RouteDocumentation;

use Attribute;

#[Attribute]
abstract readonly class RouteDoc
{
    public function __construct(
        public string $endpoint
    ) {}
}
