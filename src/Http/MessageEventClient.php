<?php

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\InvalidArgumentException;
use Illuminate\Support\Str;

class MessageEventClient extends CamundaClient
{
    /**
     * @param  array{messageName: string, businessKey: string, variables?: array}  $args
     */
    public static function start(...$args): ProcessInstanceData
    {
        $variables = $args['variables'] ?? [];
        $messageName = $args['messageName'] ?? null;
        /** @var string */
        $businessKey = $args['businessKey'] ?? null;

        if (! $messageName) {
            throw new InvalidArgumentException('Arg messageName cannot be null');
        }

        if (! $businessKey) {
            throw new InvalidArgumentException('Arg businessKey cannot be null');
        }

        $payload = [];

        $payload['businessKey'] = $businessKey;
        $payload['messageName'] = $messageName;

        if (! empty($variables) && count($variables) !== 0) {
            $payload['processVariables'] = $variables;
            $payload['resultEnabled'] = true;
            $payload['variablesInResultEnabled'] = true;
        }
        $payload['processInstanceId'] = Str::uuid()->toString();

        $response = self::make()->post('message', $payload);
        if ($response->successful() && isset($payload['variablesInResultEnabled'])) {
            /** @var array */
            $array = $response->json();

            return new ProcessInstanceData(...$array[0]['processInstance'] + ['variables' => $array[0]['variables']]);
        } elseif ($response->successful()) {
            return ProcessInstanceClient::findByBusinessKey($businessKey);
        }

        throw new InvalidArgumentException($response->body());
    }
}
