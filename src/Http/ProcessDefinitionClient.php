<?php

namespace RapidKit\LaravelCamundaClient\Http;

use RapidKit\LaravelCamundaClient\Data\ProcessDefinitionData;
use RapidKit\LaravelCamundaClient\Data\ProcessInstanceData;
use RapidKit\LaravelCamundaClient\Exceptions\InvalidArgumentException;
use RapidKit\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

class ProcessDefinitionClient extends CamundaClient
{
    /**
     * @param  array{key: string, variables?: array, businessKey?: string}  $args
     */
    public static function start(...$args): ProcessInstanceData
    {
        /** @var array */
        $variables = $args['variables'] ?? [];
        $businessKey = $args['businessKey'] ?? null;
        $payload = [];

        if (! empty($variables) && count($variables) !== 0) {
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

    /**
     * @param  array  $args
     */
    public static function xml(...$args): string
    {
        $path = self::makeIdentifierPath(path: 'process-definition/{identifier}/xml', args: $args);
        /** @var string */
        $string = self::make()->get($path)->json('bpmn20Xml');

        return $string;
    }

    public static function get(array $parameters = []): array
    {
        $processDefinition = [];
        /** @var array<array> */
        $array = self::make()->get('process-definition', $parameters)->json();
        foreach ($array as $res) {
            $processDefinition[] = ProcessDefinitionData::fromArray($res);
        }

        return $processDefinition;
    }

    /**
     * @param  array  $args
     */
    public static function find(...$args): ProcessDefinitionData
    {
        $response = self::make()->get(self::makeIdentifierPath('process-definition/{identifier}', $args));

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        /** @var array */
        $array = $response->json();

        return new ProcessDefinitionData(...$array);
    }
}
