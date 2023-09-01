<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\TaskData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\CamundaException;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use Laravolt\Camunda\Dto\Casters\VariablesCaster;

class TaskClient extends CamundaClient
{
    public static function find(string $id): TaskData
    {
        $response = self::make()->get("task/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        /** @var array */
        $payload = $response->json();

        return new TaskData(...$payload);
    }

    public static function getByProcessInstanceId(string $id): array
    {
        $response = self::make()->get("task?processInstanceId=$id");

        $data = [];
        if ($response->successful()) {
            /** @var array */
            $array = $response->json();
            foreach ($array as $task) {
                $data[] = new TaskData(...$task);
            }
        }

        return $data;
    }

    public static function get(array $payload): array
    {
        $response = self::make()->get('task', $payload);

        $data = [];
        if ($response->successful()) {
            /** @var array */
            $array = $response->json();
            foreach ($array as $task) {
                $data[] = new TaskData(...$task);
            }
        }

        return $data;
    }

    public static function unfinishedTask(array $payload): array
    {
        $payload['unfinished'] = true;

        return self::get($payload);
    }

    public static function getByProcessInstanceIds(array $ids): array
    {
        $response = self::make()->get('task?processInstanceIdIn='.implode(',', $ids));

        $data = [];
        if ($response->successful()) {
            /** @var array */
            $array = $response->json();
            foreach ($array as $task) {
                $data[] = new TaskData(...$task);
            }
        }

        return $data;
    }

    public static function claim(string $id, string $userId): bool
    {
        $response = self::make()->post("task/$id/claim", [
            'userId' => $userId,
        ]);

        if ($response->successful()) {
            return true;
        }

        return false;
    }

    public static function unclaim(string $id): bool
    {
        $response = self::make()->post("task/$id/unclam");

        if ($response->successful()) {
            return true;
        }

        return false;
    }

    public static function assign(string $id, string $userId): bool
    {
        $response = self::make()->post("task/$id/assignee", [
            'userId' => $userId,
        ]);

        if ($response->successful()) {
            return true;
        }

        return false;
    }

    public static function getByAssignedAndProcessInstanceId($user_id, array $ids = []): array
    {
        $payload = [
            'assignee' => $user_id,
        ];
        if ($ids != []) {
            $payload['processInstanceIdIn'] = implode(',', $ids);
        }

        $response = self::make()->get('task', $payload);

        $data = [];
        if ($response->successful()) {
            /** @var array */
            $array = $response->json();
            foreach ($array as $task) {
                $data[] = new TaskData(...$task);
            }
        }

        return $data;
    }

    public static function submit(string $id, array $variables): bool
    {
        $varData = (object) [];
        if (! empty($variables)) {
            $varData = (object) $variables;
        }
        $response = self::make()->post(
            "task/$id/submit-form",
            ['variables' => $varData]
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function submitAndReturnVariables(string $id, array $variables): array
    {
        $response = self::make()->post(
            "task/$id/submit-form",
            ['variables' => $variables, 'withVariablesInReturn' => true]
        );

        if ($response->status() === 200) {
            return (new VariablesCaster(['array'], Variable::class))->cast($response->json());
        }

        throw new CamundaException($response->body(), $response->status());
    }
}
