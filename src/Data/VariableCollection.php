<?php

namespace RapidKit\LaravelCamundaClient\Data;

use Illuminate\Support\Collection;
use RapidKit\LaravelCamundaClient\Data\Types\BooleanType;
use RapidKit\LaravelCamundaClient\Data\Types\JsonType;
use RapidKit\LaravelCamundaClient\Data\Types\ObjectType;
use RapidKit\LaravelCamundaClient\Data\Types\StringType;

/**
 * @template TKey of array-key
 * @template TModel of VariableCollection
 *
 * @extends \Illuminate\Support\Collection<TKey, TModel>
 */
class VariableCollection extends Collection
{
    public function toArray(): array
    {
        $variables = [];
        foreach ($this->items as $key => $value) {
            /** @var string */
            $valueType = gettype($value);
            $typeClass = match ($valueType) {
                'array' => JsonType::class,
                'boolean' => BooleanType::class,
                'object' => ObjectType::class,
                default => StringType::class,
            };

            $variables[$key] = (new $typeClass)($value);
        }

        return $variables;
    }
}
