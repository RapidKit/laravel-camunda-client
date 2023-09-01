<?php

namespace BeyondCRUD\LaravelCamundaClient\Commands;

use Illuminate\Console\Command;

class LaravelCamundaClientCommand extends Command
{
    public $signature = 'laravel-camunda-client';

    public function handle(): int
    {
        $this->comment(config('camunda-client.url'));

        return self::SUCCESS;
    }
}
