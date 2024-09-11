<?php

use RapidKit\LaravelCamundaClient\Data\ProcessInstanceData;
use RapidKit\LaravelCamundaClient\Http\DeploymentClient;
use RapidKit\LaravelCamundaClient\Http\MessageEventClient;

beforeEach(function () {
    DeploymentClient::create('External Task', __DIR__.'/../../resources/bpmn/simple.bpmn');
});

afterEach(function () {
    DeploymentClient::truncate(true);
});

it('can start message event', function () {
    $businessKey = 'businessKey';
    $processInstanceData = MessageEventClient::start(
        messageName: 'MessageStartEvent',
        businessKey: $businessKey,
    );

    expect($processInstanceData)->toBeInstanceOf(ProcessInstanceData::class);
    expect($processInstanceData->businessKey)->toBe($businessKey);
});

it('can start message event with variables', function () {
    $businessKey = 'businessKey';
    $vars = ['title' => ['type' => 'String', 'value' => 'Sample Title']];
    $processInstanceData = MessageEventClient::start(
        messageName: 'MessageStartEvent',
        businessKey: $businessKey,
        variables: $vars,
    );

    expect($processInstanceData)->toBeInstanceOf(ProcessInstanceData::class);
    expect($processInstanceData->businessKey)->toBe($businessKey);
    expect($processInstanceData->variables)->toHaveCount(1);

    foreach ($processInstanceData->variables as $key => $value) {
        unset($value['valueInfo']);
        expect($value)->toBe($vars[$key]);
    }
});
