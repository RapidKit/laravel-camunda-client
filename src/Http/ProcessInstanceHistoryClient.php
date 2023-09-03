<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceHistoryData;
use BeyondCRUD\LaravelCamundaClient\Data\VariableData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

class ProcessInstanceHistoryClient extends CamundaClient
{
    /**
     * @return array<int<0, max>, ProcessInstanceHistoryData>
     */
    public static function get(array $parameters = []): array
    {
        $instances = [];
        /** @var array<array> */
        $array = self::make()->get('history/process-instance', $parameters)->json();
        foreach ($array as $res) {
            $instances[] = ProcessInstanceHistoryData::fromArray($res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstanceHistoryData
    {
        $response = self::make()->get("history/process-instance/$id");

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        /** @var array */
        $array = $response->json();

        return ProcessInstanceHistoryData::fromArray($array);
    }

    public static function variables(string $id): array
    {
        return VariableData::fromResponse(
            self::make()->get('history/variable-instance', ['processInstanceId' => $id])
        );
    }
}
