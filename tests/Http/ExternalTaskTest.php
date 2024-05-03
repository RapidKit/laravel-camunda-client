<?php

use RapidKit\LaravelCamundaClient\Http\DeploymentClient;
use RapidKit\LaravelCamundaClient\Http\ExternalTaskClient;
use RapidKit\LaravelCamundaClient\Http\ProcessDefinitionClient;
use RapidKit\LaravelCamundaClient\Http\ProcessInstanceHistoryClient;

beforeEach(function () {
    DeploymentClient::create('External Task', __DIR__.'/../../resources/bpmn/external-task.bpmn');

    $variables = ['signature' => ['value' => 'Fulan', 'type' => 'string']];
    $businessKey = 'key-1';

    ProcessDefinitionClient::start(key: 'processExternalTask', variables: $variables, businessKey: $businessKey);
});

afterEach(function () {
    DeploymentClient::truncate(true);
});

it('can fetch and lock', function () {
    $topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
    $tasks = ExternalTaskClient::fetchAndLock('worker1', $topics);

    expect($tasks)->toHaveCount(1);
});

it('can complete task', function () {
    $topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
    $tasks = ExternalTaskClient::fetchAndLock('worker1', $topics);

    expect($tasks)->toHaveCount(1);

    $task = $tasks[0];

    expect(ExternalTaskClient::complete($task->id, 'worker1'))->toBeTrue();
});

it('can complete task with variables', function () {
    $topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
    $tasks = ExternalTaskClient::fetchAndLock('worker1', $topics);

    expect($tasks)->toHaveCount(1);

    $task = $tasks[0];
    $vars = ['title' => ['value' => 'Sample Title', 'type' => 'string']];

    expect(ExternalTaskClient::complete($task->id, 'worker1', $vars))->toBeTrue();

    $history = ProcessInstanceHistoryClient::find($task->processInstanceId);

    expect($history->state)->toBe('COMPLETED');

    $vars = ProcessInstanceHistoryClient::variables($history->id);

    expect($vars)->toHaveKey('signature')->toHaveKey('title');
});

it('can unlock locked task', function () {
    $topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
    $tasks = ExternalTaskClient::fetchAndLock('worker1', $topics);

    expect($tasks)->toHaveCount(1);

    $task = $tasks[0];

    expect(ExternalTaskClient::unlock($task->id))->toBeTrue();
});

it('can unlock unlocked task', function () {
    $topics = [['topicName' => 'pdf', 'lockDuration' => 600_000]];
    $tasks = ExternalTaskClient::fetchAndLock('worker1', $topics);

    expect($tasks)->toHaveCount(1);

    $task = $tasks[0];

    expect(ExternalTaskClient::unlock($task->id))->toBeTrue();
});
