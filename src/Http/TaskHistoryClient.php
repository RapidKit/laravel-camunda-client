<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\TaskHistoryData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\CamundaException;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class TaskHistoryClient extends CamundaClient
{
    public static function find(string $id): TaskHistoryData
    {
        $response = self::make()->get("history/task?taskId=$id");

        if ($response->status() === 200) {
            if (empty($response->json())) {
                throw new ObjectNotFoundException(sprintf('Cannot find task history with ID = %s', $id));
            }

            /** @var array */
            $array = $response->json();

            /** @var array[string, mixed] */
            $payloads = Arr::first($array);

            return TaskHistoryData::fromArray($payloads);
        }

        /** @var string */
        $message = $response->json('message');

        throw new CamundaException($message);
    }

    public static function getByProcessInstanceId(string $processInstanceId): Collection
    {
        $response = self::make()->get('history/task', ['processInstanceId' => $processInstanceId, 'finished' => true]);

        if ($response->successful()) {
            $data = [];

            /** @var array */
            $array = $response->json();

            foreach ($array as $task) {
                array_push($data, TaskHistoryData::fromArray($task));
            }

            return collect($data)->sortBy('endTime');
        }

        return [];
    }
}
