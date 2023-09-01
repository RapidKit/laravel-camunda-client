<?php

use BeyondCRUD\LaravelCamundaClient\Commands\LaravelCamundaClientCommand;

use function Pest\Laravel\artisan;

it('can output the configured value', function () {
    artisan(LaravelCamundaClientCommand::class)
        ->expectsOutput(config('camunda-client.url'))
        ->assertExitCode(0);
});

it('can output the another value', function () {
    $currentValue = 'http://localhost:8039/engine-rest';

    config()->set('camunda-client.url', $currentValue);

    artisan(LaravelCamundaClientCommand::class)
        ->expectsOutput($currentValue)
        ->assertExitCode(0);
});
