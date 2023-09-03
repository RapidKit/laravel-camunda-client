<?php

use BeyondCRUD\LaravelCamundaClient\Data\TaskHistoryData;
use BeyondCRUD\LaravelCamundaClient\Data\VariableData;
use BeyondCRUD\LaravelCamundaClient\Exceptions\CamundaException;
use BeyondCRUD\LaravelCamundaClient\Exceptions\ObjectNotFoundException;
use BeyondCRUD\LaravelCamundaClient\Http\DeploymentClient;
use BeyondCRUD\LaravelCamundaClient\Http\ProcessDefinitionClient;
use BeyondCRUD\LaravelCamundaClient\Http\TaskClient;
use BeyondCRUD\LaravelCamundaClient\Http\TaskHistoryClient;

beforeEach(fn () => DeploymentClient::create('test', __DIR__.'/../../resources/bpmn/simple.bpmn'));
afterEach(fn () => DeploymentClient::truncate(true));

it('can find by id and ids', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

    foreach ($tasks as $task) {
        $taskRes = TaskClient::find($task->id);

        expect($task->id)->toEqual($taskRes->id);
    }

    $tasks = TaskClient::getByProcessInstanceIds([$processInstance->id]);

    foreach ($tasks as $task) {
        $taskRes = TaskClient::find($task->id);

        expect($task->id)->toEqual($taskRes->id);
    }
});

it('can find by ids is empty', function () {
    $tasks = TaskClient::getByProcessInstanceIds([]);
    expect($tasks)->toBeEmpty();
});

it("can't get invalid id", function () {
    TaskClient::find('invalid-id');
})->throws(ObjectNotFoundException::class);

it('can get completed task', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

    foreach ($tasks as $task) {
        TaskClient::submit(
            $task->id,
            ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']]
        );
        $completedTask = TaskHistoryClient::find($task->id);
        expect($completedTask)->toBeInstanceOf(TaskHistoryData::class);
    }
});

it("can't completed task with invalid id", function () {
    TaskHistoryClient::find('invalid-id');
})->throws(ObjectNotFoundException::class);

it('can get completed tasks', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

    foreach ($tasks as $task) {
        TaskClient::submit(
            $task->id,
            ['email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string']],
        );
    }

    $completedTasks = TaskHistoryClient::getByProcessInstanceId($processInstance->id);
    expect($completedTasks)->toHaveCount(1);
});

it('can submit form', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

    foreach ($tasks as $task) {
        $submitted = TaskClient::submit($task->id, [
            'email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string'],
        ]);
        expect($submitted)->toBeTrue();
    }
});

it('can submit form and return variables', function () {
    $vars = ['title' => ['value' => 'Foo', 'type' => 'string']];
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars);
    $tasks = TaskClient::getByProcessInstanceId($processInstance->id);

    foreach ($tasks as $task) {
        $variables = TaskClient::submitAndReturnVariables($task->id, [
            'email' => ['value' => 'uyab.exe@gmail.com', 'type' => 'string'],
        ]);

        expect($variables)->toHaveCount(2);
        expect($variables['title'])->toBeInstanceOf(VariableData::class);
    }
});

it("can't submit form with invalid id", function () {
    TaskClient::submit('invalid-id', ['foo' => 'bar']);
})->throws(CamundaException::class);

it("can't submit form and return variables with invalid id", function () {
    TaskClient::submitAndReturnVariables('invalid-id', ['foo' => 'bar']);
})->throws(CamundaException::class);
