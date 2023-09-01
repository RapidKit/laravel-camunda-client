<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Spatie\LaravelData\Data;

class VariableData extends Data
{
    public function __construct(
        public string $name,
        public string $type,
        public mixed $value,
        public ?array $valueInfo = [],
    ) {
    }
}
