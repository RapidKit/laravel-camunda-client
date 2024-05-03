<?php

use RapidKit\LaravelCamundaClient\Data\ProcessDefinitionData;
use RapidKit\LaravelCamundaClient\Exceptions\InvalidArgumentException;
use RapidKit\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use RapidKit\LaravelCamundaClient\Http\DeploymentClient;
use RapidKit\LaravelCamundaClient\Http\ProcessDefinitionClient;

afterEach(function () {
    DeploymentClient::truncate(true);
});

describe('deploy sample BPMN', function () {
    beforeEach(function () {
        DeploymentClient::create('test', __DIR__.'/../../resources/bpmn/simple.bpmn');
    });

    it('can start new process instance', function () {
        $variables = ['title' => ['value' => 'Sample Title', 'type' => 'string']];
        $businessKey = 'key-1';
        $processInstance = ProcessDefinitionClient::start(
            key: 'process_1',
            variables: $variables,
            businessKey: $businessKey,
        );

        expect($processInstance->id)->toBeString();
    });

    it('can start with empty variables', function () {
        $variables = [];
        $businessKey = 'key-1';
        $processInstance = ProcessDefinitionClient::start(
            key: 'process_1',
            variables: $variables,
            businessKey: $businessKey,
        );

        expect($processInstance->id)->toBeString();
    });

    it('can get list process definition', function () {
        $processDefinitions = ProcessDefinitionClient::get();

        expect($processDefinitions)->toBeArray()->toHaveCount(1);
        expect($processDefinitions[0])->toBeInstanceOf(\RapidKit\LaravelCamundaClient\Data\ProcessDefinitionData::class);
    });

    it('can find by id', function () {
        $processDefinitions = ProcessDefinitionClient::get();
        $processDefinition = ProcessDefinitionClient::find($processDefinitions[0]->id);

        expect($processDefinition)->not->toBeNull();

        $processDefinition = ProcessDefinitionClient::find(id: $processDefinitions[0]->id);

        expect($processDefinition)->not->toBeNull();
    });

    it('can find by invalid id', function () {
        ProcessDefinitionClient::find(id: 'invalid-id');
    })->throws(ObjectNotFoundException::class);

    it('can find by key', function () {
        $processDefinition = ProcessDefinitionClient::find(key: 'process_1');

        expect($processDefinition)->toBeInstanceOf(ProcessDefinitionData::class);
    });

    it('can find by invalid key', function () {
        ProcessDefinitionClient::find(key: 'invalid-key');
    })->throws(ObjectNotFoundException::class);

    it('can find xml by id', function () {
        $processDefinitions = ProcessDefinitionClient::get();
        $xml = ProcessDefinitionClient::xml(id: $processDefinitions[0]->id);

        expect($xml)->not->toBeNull();
    });

    it('can find xml by key', function () {
        $xml = ProcessDefinitionClient::xml(key: 'process_1');

        expect($xml)->not->toBeNull();
    });
});

it('can find xml without id or key', function () {
    ProcessDefinitionClient::xml();
})->throws(InvalidArgumentException::class);

it('can find by key and tenant id', function () {
    $tenantId = 'tenant-1';

    config()->set('camunda-client.tenant_id', $tenantId);

    DeploymentClient::create('test', __DIR__.'/../../resources/bpmn/simple.bpmn');

    $processDefinition = ProcessDefinitionClient::find(key: 'process_1', tenantId: $tenantId);

    expect($processDefinition->tenantId)->toBe($tenantId);
});
