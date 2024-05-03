<?php

namespace RapidKit\LaravelCamundaClient\Http;

use RapidKit\LaravelCamundaClient\Data\ProcessInstanceData;
use RapidKit\LaravelCamundaClient\Data\VariableData;
use RapidKit\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

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
        /** @var array<array> */
        $array = self::make()->get('process-instance', $parameters)->json();
        foreach ($array as $res) {
            $instances[] = new ProcessInstanceData(...$res);
        }

        return $instances;
    }

    public static function getByVariables(array $variables = []): array
    {
        /**
         * operator can only contain _eq_, _neq_, _gt_, _gte_, _lt_, _lte_. Check Camunda documentation for more information
         */
        $instances = [];

        if (count($variables) > 0) {
            $reqstr = 'process-instance?variables=';
            foreach ($variables as $variable) {
                $reqstr .= $variable['name'].$variable['operator'].$variable['value'].',';
            }
        } else {
            $reqstr = 'process-instance';
        }

        /** @var array<array> */
        $array = self::make()->get($reqstr)->json();
        foreach ($array as $res) {
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
        $response = self::make()->get('process-instance?businessKey='.$businessKey);

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

        /** @var array */
        $array = $data[count($data) - 1];

        return new ProcessInstanceData(...$array);
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
