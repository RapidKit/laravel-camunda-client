<?php

use BeyondCRUD\LaravelCamundaClient\Data\ProcessInstanceData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use BeyondCRUD\LaravelCamundaClient\Http\DeploymentClient;
use BeyondCRUD\LaravelCamundaClient\Http\ProcessDefinitionClient;
use BeyondCRUD\LaravelCamundaClient\Http\ProcessInstanceClient;

beforeEach(function () {
    DeploymentClient::truncate(true);
    DeploymentClient::create('test', __DIR__.'/../../resources/bpmn/simple.bpmn');
});

it('can find by id', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance1 = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $processInstance2 = ProcessInstanceClient::find(id: $processInstance1->id);
    $processInstance3 = ProcessInstanceClient::find($processInstance1->id);

    expect($processInstance1->id)->toBe($processInstance2->id);
    expect($processInstance2->id)->toBe($processInstance3->id);
});

it('can get list all', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $processInstances = ProcessInstanceClient::get();

    foreach ($processInstances as $process) {
        expect($process)->toBeInstanceOf(ProcessInstanceData::class);
        expect($process->definitionId)->toContain('process_1');
    }
});

it('can get list by parameters', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    ProcessDefinitionClient::start(key: 'process_1', variables: $vars, businessKey: '001');

    $processInstances = ProcessInstanceClient::get(['businessKey' => '001']);

    expect($processInstances)->toHaveCount(1);

    $processInstances = ProcessInstanceClient::get(['businessKey' => '002']);

    expect($processInstances)->toHaveCount(0);
});

it('can get variables', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $vars = ProcessInstanceClient::variables($processInstance->id);

    expect($vars)->toHaveCount(1);
    expect($vars['title']->type)->toBe('String');
    expect($vars['title']->value)->toBe('Foo');
});

it('can delete', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $deleted = ProcessInstanceClient::delete($processInstance->id);

    expect($deleted)->toBeTrue();

    ProcessInstanceClient::find($processInstance->id);
})->throws(ObjectNotFoundException::class);
