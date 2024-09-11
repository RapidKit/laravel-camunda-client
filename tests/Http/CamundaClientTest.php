<?php

use RapidKit\LaravelCamundaClient\Http\CamundaClient;

it('call valid endpoint', function () {
    $response = CamundaClient::make()->get('version');

    expect($response->status())->toBe(200);
    expect($response->json())->toHaveKey('version');
    expect($response->object())->toBeInstanceOf(\stdClass::class);
});

it('call invalid endpoint', function () {
    $response = CamundaClient::make()->get('invalid-endpoint');

    expect($response->status())->toBe(404);
});
