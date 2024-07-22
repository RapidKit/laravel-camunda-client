<?php

namespace RapidKit\LaravelCamundaClient\Data\Types;

class JsonType
{
    /**
     * @throws \JsonException
     */
    public function __invoke(array $value): array
    {
        return [
            'value' => json_encode($value, JSON_THROW_ON_ERROR),
            'type' => 'Json',
        ];
    }
}
