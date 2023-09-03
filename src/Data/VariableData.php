<?php

namespace BeyondCRUD\LaravelCamundaClient\Data;

use Illuminate\Http\Client\Response;
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

    /**
     * @return self[]
     */
    public static function fromResponse(Response $res): array
    {
        /** @var array<array> */
        $vars = $res->json();

        $result = [];
        foreach ($vars as $key => $value) {
            $name = isset($value['name']) ? $value['name'] : $key;
            $result[$name] = new VariableData(...[
                'name' => $name,
                'type' => $value['type'],
                'value' => $value['value'],
                'valueInfo' => $value['valueInfo'] ?? [],
            ]);
        }

        return $result;
    }
}
