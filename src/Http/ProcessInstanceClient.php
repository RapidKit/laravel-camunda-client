<?php

declare(strict_types=1);

namespace Laravolt\Camunda\Http;

use Laravolt\Camunda\Dto\ProcessInstance;
use Laravolt\Camunda\Dto\Variable;
use Laravolt\Camunda\Exceptions\ObjectNotFoundException;

class ProcessInstanceClient extends CamundaClient
{
    public static function get(array $parameters = []): array
    {
        $instances = [];
        if (!$parameters) {
            $res = self::make()->get('process-instance');
        } else {
            $res = self::make()->post('process-instance', $parameters);
        }
        foreach ($res->json() as $res) {
            $instances[] = new ProcessInstance($res);
        }

        return $instances;
    }

    public static function getByVariables(array $variables = []): array
    {
        /**
         *  $variables = [
         *       [
         *          'name' => 'varname',
         *          'operator' => "eq",
         *          'value' => 'varvalue',
         *       ],
         *   ];
         */

        /**
         * `operator` can only contain `eq`, `neq`, `gt`, `gte`, `lt`, `lte`
         * Check Camunda documentation for more information
         */

        $instances = [];

        if (!$variables) {
            $res = self::make()->get('process-instance');
        } else {
            $res = self::make()->post('process-instance', [
                'variables' => $variables

            ]);
        }
        foreach ($res->json() as $res) {
            $instances[] = new ProcessInstance($res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstance
    {
        $response = self::make()->get("process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new ProcessInstance($response->json());
    }


    public static function findByBusniessKey(string $businessKey): ProcessInstance
    {

        $response = self::make()->post("process-instance", [
            'businessKey' => $businessKey
        ]);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        $data =  $response->json();

        if (sizeof($data) ==  0) {
            throw new ObjectNotFoundException("Process Instance Not Found");
        }

        return new ProcessInstance($data[sizeof($data) - 1]);
    }

    public static function variables(string $id): array
    {
        $variables = self::make()->get("process-instance/$id/variables", ['deserializeValues' => false])->json();

        return collect($variables)->mapWithKeys(
            fn ($data, $name) => [
                $name => new Variable(
                    name: $name,
                    value: $data['value'],
                    type: $data['type'],
                    valueInfo: $data['valueInfo'] ?? []
                ),
            ]
        )->toArray();
    }

    public static function delete(string $id): bool
    {
        return self::make()->delete("process-instance/$id")->status() === 204;
    }
}
