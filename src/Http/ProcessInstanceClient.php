<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceData;
use BeyondCRUD\LaravelCamundaClient\Data\VariableData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

// use Laravolt\Camunda\Dto\ProcessInstance;
// use Laravolt\Camunda\Dto\Variable;

class ProcessInstanceClient extends CamundaClient
{
    /**
     * @return ProcessInstanceData[]
     */
    public static function get(array $parameters = []): array
    {
        $instances = [];

        if (! $parameters) {
            $res = self::make()->get('process-instance');
        } else {
            $res = self::make()->post('process-instance', $parameters);
        }

        foreach ($res->json() as $res) {
            $instances[] = new ProcessInstanceData(...$res);
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

        if (! $variables) {
            $res = self::make()->get('process-instance');
        } else {
            $res = self::make()->post('process-instance', [
                'variables' => $variables,

            ]);
        }
        foreach ($res->json() as $res) {
            $instances[] = new ProcessInstanceData(...$res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstanceData
    {
        $response = self::make()->get("process-instance/$id");

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        /** @var array */
        $array = $response->json();

        return new ProcessInstanceData(...$array);
    }

    public static function findByBusinessKey(string $businessKey): ProcessInstanceData
    {

        $response = self::make()->post('process-instance', [
            'businessKey' => $businessKey,
        ]);

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        /** @var array */
        $data = $response->json();

        if (count($data) == 0) {
            throw new ObjectNotFoundException('Process Instance Not Found');
        }

        return new ProcessInstanceData(...$data[count($data) - 1]);
    }

    /**
     * @return VariableData[]
     */
    public static function variables(string $id): array
    {
        return VariableData::fromResponse(
            self::make()->get("process-instance/$id/variables", ['deserializeValues' => false])
        );
    }

    public static function delete(string $id): bool
    {
        return self::make()->delete("process-instance/$id")->status() === 204;
    }
}
