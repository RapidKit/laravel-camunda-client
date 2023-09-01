<?php

declare(strict_types=1);

namespace BeyondCRUD\LaravelCamundaClient\Http;

use BeyondCRUD\LaravelCamundaClient\Data\DeploymentData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ParseException;

class DeploymentClient extends CamundaClient
{
    public static function create(string $name, string|array $bpmnFiles): DeploymentData
    {
        /** @var string */
        $appName = config('app.name');
        /** @var string */
        $appEnv = config('app.env');
        $multipart = [
            ['name' => 'deployment-name', 'contents' => $name],
            ['name' => 'deployment-source', 'contents' => sprintf('%s (%s)', $appName, $appEnv)],
            ['name' => 'enable-duplicate-filtering', 'contents' => 'true'],
        ];

        if (config('camunda-client.tenant_id')) {
            $multipart[] = [
                'name' => 'tenant-id',
                'contents' => config('camunda-client.tenant_id'),
            ];
        }

        $request = self::make()->asMultipart();
        foreach ((array) $bpmnFiles as $bpmn) {
            $filename = pathinfo($bpmn)['basename'];
            /** @var string */
            $content = file_get_contents($bpmn);
            $request->attach($filename, $content, $filename);
        }

        $response = $request->post('deployment/create', $multipart);

        if ($response->status() === 400) {
            /** @var string */
            $message = $response->json('message');
            throw new ParseException($message);
        }

        return DeploymentData::fromResponse($response);
    }

    public static function find(string $id): DeploymentData
    {
        $response = self::make()->get("deployment/$id");

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        return DeploymentData::fromResponse($response);
    }

    public static function get(array $parameters = []): array
    {
        $response = self::make()->get('deployment', $parameters);
        $result = [];
        /** @var array[array] */
        $array = $response->json();
        foreach ($array as $data) {
            $result[] = DeploymentData::fromArray($data);
        }

        return $result;
    }

    public static function truncate(bool $cascade = false): void
    {
        $deployments = self::get();
        foreach ($deployments as $deployment) {
            self::delete($deployment->id, $cascade);
        }
    }

    public static function delete(string $id, bool $cascade = false): bool
    {
        $cascadeFlag = $cascade ? 'cascade=true' : '';
        $response = self::make()->delete("deployment/{$id}?" . $cascadeFlag);

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        return $response->status() === 204;
    }
}
