<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceHistoryData;
use BeyondCRUD\LaravelCamundaClient\Data\VariableData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

class ProcessInstanceHistoryClient extends CamundaClient
{
    /**
     * @return ProcessInstanceHistory[]
     */
    public static function get(array $parameters = []): array
    {
        $instances = [];
        foreach (self::make()->get('history/process-instance', $parameters)->json() as $res) {
            $instances[] = new ProcessInstanceHistoryData(...$res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstanceHistoryData
    {
        $response = self::make()->get("history/process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
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
