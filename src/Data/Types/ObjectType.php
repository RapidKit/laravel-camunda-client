<?php

namespace RapidKit\LaravelCamundaClient\Data\Types;

use Illuminate\Support\Collection;

class ObjectType
{
    /**
     * @throws \JsonException
     */
    public function __invoke(mixed $value): array
    {
        if ($value instanceof Collection) {
            return [
                'value' => $value->toJson(),
                'type' => 'Object',
                'valueInfo' => [
                    'objectTypeName' => 'java.util.Collection',
                    'serializationDataFormat' => 'application/json',
                ],
            ];
        }

        return [
            'value' => json_encode($value, JSON_THROW_ON_ERROR),
            'type' => 'Object',
        ];
    }
}
