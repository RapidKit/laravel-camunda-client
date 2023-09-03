<?php

namespace BeyondCRUD\LaravelCamundaClient\Data\Types;

class BooleanType
{
    public function __invoke(mixed $value): array
    {
        return ['value' => (bool) $value, 'type' => 'Boolean'];
    }
}
