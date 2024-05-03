<?php

use BeyondCRUD\LaravelCamundaClient\Commands\ConsumeExternalTaskCommand;

use function Pest\Laravel\artisan;

it('can run the command', function () {
    artisan(ConsumeExternalTaskCommand::class, ['--workerId' => 'test-worker'])
        // ->expectsOutput('topic')
        // ->expectsOutput('Job')
        // ->expectsOutput('Job Dispatched')
        ->assertExitCode(0);
});
