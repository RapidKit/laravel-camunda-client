<?php

use BeyondCRUD\LaravelCamundaClient\BPMNReader;

it('can parse form definition', function () {
    $file = __DIR__.'/../resources/bpmn/rekrutmen.bpmn';
    $reader = new BPMNReader($file);
    $forms = $reader->getForms();

    expect($forms)->not->toBeEmpty();
});

it('can parse empty form definition', function () {
    $file = __DIR__.'/../resources/bpmn/simple.bpmn';
    $reader = new BPMNReader($file);
    $forms = $reader->getForms();

    expect($forms)->toBeEmpty();
});
