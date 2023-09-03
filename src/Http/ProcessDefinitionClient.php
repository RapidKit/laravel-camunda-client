<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\ProcessDefinitionData;
use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\InvalidArgumentException;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

class ProcessDefinitionClient extends CamundaClient
{
    /**
     * @param  array{key: string, variables?: array, businessKey?: string}  $args
     */
    public static function start(...$args): ProcessInstanceData
    {
        $variables = $args['variables'] ?? (object) [];
        $businessKey = $args['businessKey'] ?? null;
        $payload = [];

        if (! empty($variables)) {
            $payload['variables'] = $variables;
            $payload['withVariablesInReturn'] = true;
        }
        if ($businessKey) {
            $payload['businessKey'] = $businessKey;
        }

        $path = self::makeIdentifierPath('process-definition/{identifier}/start', $args);
        $response = self::make()->post($path, $payload);
        if ($response->successful()) {
            /** @var array */
            $array = $response->json();

            return new ProcessInstanceData(...$array);
        }

        throw new InvalidArgumentException($response->body());
    }

    public static function xml(...$args): string
    {
        $path = self::makeIdentifierPath(path: 'process-definition/{identifier}/xml', args: $args);

        return self::make()->get($path)->json('bpmn20Xml');
    }

    public static function get(array $parameters = []): array
    {
        $processDefinition = [];
        foreach (self::make()->get('process-definition', $parameters)->json() as $res) {
            $processDefinition[] = new ProcessDefinitionData(...$res);
        }

        return $processDefinition;
    }

    public static function find(...$args): ProcessDefinitionData
    {
        $response = self::make()->get(self::makeIdentifierPath('process-definition/{identifier}', $args));

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        /** @var array */
        $array = $response->json();

        return new ProcessDefinitionData(...$array);
    }
}
