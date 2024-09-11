<?php

namespace RapidKit\LaravelCamundaClient\Http;

use RapidKit\LaravelCamundaClient\Data\TenantData;
use RapidKit\LaravelCamundaClient\Exceptions\CamundaException;
use RapidKit\LaravelCamundaClient\Exceptions\ObjectNotFoundException;

class TenantClient extends CamundaClient
{
    public static function find(string $id): TenantData
    {
        $response = self::make()->get("tenant/$id");

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        /** @var array */
        $payloads = $response->json();

        return new TenantData(...$payloads);
    }

    public static function get(array $parameters = []): array
    {
        $response = self::make()->get('tenant', $parameters);
        $result = [];
        /** @var array */
        $array = $response->json();
        foreach ($array as $data) {
            $result[] = new TenantData(...$data);
        }

        return $result;
    }

    public static function create(string $id, string $name): bool
    {
        $response = self::make()->post(
            'tenant/create',
            compact('id', 'name')
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function delete(string $id): bool
    {
        $response = self::make()->delete("tenant/{$id}");

        if ($response->status() === 404) {
            /** @var string */
            $message = $response->json('message');
            throw new ObjectNotFoundException($message);
        }

        return $response->status() === 204;
    }

    public static function truncate(): void
    {
        foreach (self::get() as $tenant) {
            self::delete($tenant->id);
        }
    }
}
