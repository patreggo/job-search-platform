<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Filterable
{
    public function __construct(
        public readonly string $name,
        public readonly string $type = 'exact',
        public readonly ?string $field = null,
        public readonly ?string $join = null,
        public readonly array $options = []
    ) {}
}