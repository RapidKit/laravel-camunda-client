<?php

use BeyondCRUD\LaravelCamundaClient\Data\VariableCollection;
use BeyondCRUD\LaravelCamundaClient\Http\DeploymentClient;
use BeyondCRUD\LaravelCamundaClient\Http\ProcessDefinitionClient;
use BeyondCRUD\LaravelCamundaClient\Http\ProcessInstanceClient;

beforeEach(fn () => DeploymentClient::create('test', __DIR__.'/../../resources/bpmn/simple.bpmn'));
afterEach(fn () => DeploymentClient::truncate(true));

it('can automatically format variables', function () {
    $raws = [
        'title' => 'Some string',
        'isActive' => true,
        'tags' => ['satu' => 'foo', 'dua', 'tiga'],
        'keys' => collect([1, 2]),
    ];
    $vars = new VariableCollection($raws);
    $processInstance = ProcessDefinitionClient::start(key: 'process_1', variables: $vars->toArray());
    $camundaVars = ProcessInstanceClient::variables($processInstance->id);

    expect($camundaVars['title']->type)->toEqual('String');
    expect($camundaVars['isActive']->type)->toEqual('Boolean');
    expect($camundaVars['tags']->type)->toEqual('Json');
    expect($camundaVars['keys']->type)->toEqual('Object');
});
