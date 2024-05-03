<?php

namespace BeyondCRUD\LaravelCamundaClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BeyondCRUD\LaravelCamundaClient\LaravelCamundaClient
 */
class LaravelCamundaClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BeyondCRUD\LaravelCamundaClient\LaravelCamundaClient::class;
    }
}
