<?php

namespace RapidKit\LaravelCamundaClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RapidKit\LaravelCamundaClient\LaravelCamundaClient
 */
class LaravelCamundaClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RapidKit\LaravelCamundaClient\LaravelCamundaClient::class;
    }
}
