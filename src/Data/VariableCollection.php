<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use BeyondCRUD\LaravelCamundaClient\Data\Types\BooleanType;
use BeyondCRUD\LaravelCamundaClient\Data\Types\JsonType;
use BeyondCRUD\LaravelCamundaClient\Data\Types\ObjectType;
use BeyondCRUD\LaravelCamundaClient\Data\Types\StringType;
use Illuminate\Support\Collection;

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

            $variables[$key] = (new $typeClass())($value);
        }

        return $variables;
    }
}
