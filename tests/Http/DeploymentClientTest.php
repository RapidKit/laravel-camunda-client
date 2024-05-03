<?php

use RapidKit\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use RapidKit\LaravelCamundaClient\Exceptions\ParseException;
use RapidKit\LaravelCamundaClient\Http\DeploymentClient;

afterEach(function () {
    DeploymentClient::truncate(true);
});

it('should deploy bpmn', function () {
    $deploymentName = 'test';
    $file = __DIR__.'/../../resources/bpmn/simple.bpmn';
    $deployment = DeploymentClient::create($deploymentName, $file);

    expect($deployment->name)->toBe($deploymentName);
});

it('can deploy bpmn with tenant_id', function () {
    $deploymentName = 'test-tenant-id';
    $tenantID = 'sample-tenant';
    $file = __DIR__.'/../../resources/bpmn/simple.bpmn';

    config()->set('camunda-client.tenant_id', $tenantID);

    $deployment = DeploymentClient::create($deploymentName, $file);

    expect($deployment->name)->toBe($deploymentName);
    expect($deployment->tenantId)->toBe($tenantID);
});

it('can deploy multiple bpmn', function () {
    $deploymentName = 'test';
    $files = [
        __DIR__.'/../../resources/bpmn/external-task.bpmn',
        __DIR__.'/../../resources/bpmn/simple.bpmn',
        __DIR__.'/../../resources/bpmn/simple2.bpmn',
    ];

    $deployment = DeploymentClient::create('test', $files);
    expect($deployment->name)->toBe($deploymentName);
});

it("can't deploy invalid bpmn", function () {
    $files = __DIR__.'/../../resources/bpmn/invalid.bpmn';

    DeploymentClient::create('test', $files);
})->throws(ParseException::class);

it('can get deployment by id', function () {
    $deploymentName = 'test';
    $file = __DIR__.'/../../resources/bpmn/simple.bpmn';
    $deployment = DeploymentClient::create($deploymentName, $file);

    expect(DeploymentClient::find($deployment->id)->id)->toBe($deployment->id);
});

it("can't get deployment by invalid id", function () {
    DeploymentClient::find('some-invalid-id');
})->throws(ObjectNotFoundException::class);

it('can get list deployment', function () {
    $deploymentName = 'test';
    $file = __DIR__.'/../../resources/bpmn/simple.bpmn';

    DeploymentClient::create($deploymentName, $file);

    expect(DeploymentClient::get())->toHaveCount(1);

    $file = __DIR__.'/../../resources/bpmn/simple2.bpmn';

    DeploymentClient::create($deploymentName, $file);

    expect(DeploymentClient::get())->toHaveCount(2);
});

it('can delete deployment', function () {
    $deploymentName = 'test';
    $file = __DIR__.'/../../resources/bpmn/simple.bpmn';
    $deployment = DeploymentClient::create($deploymentName, $file);

    expect(DeploymentClient::delete($deployment->id))->toBeTrue();
});

it("can't delete invalid deployment", function () {
    DeploymentClient::delete('invalid-id');
})->throws(ObjectNotFoundException::class);

it('can truncate deployment', function () {
    DeploymentClient::create('test1', __DIR__.'/../../resources/bpmn/external-task.bpmn');

    expect(DeploymentClient::get())->toHaveCount(1);

    DeploymentClient::create('test2', [
        __DIR__.'/../../resources/bpmn/simple.bpmn',
        __DIR__.'/../../resources/bpmn/simple2.bpmn',
    ]);

    expect(DeploymentClient::get())->toHaveCount(2);

    DeploymentClient::truncate(true);

    expect(DeploymentClient::get())->toHaveCount(0);
});
