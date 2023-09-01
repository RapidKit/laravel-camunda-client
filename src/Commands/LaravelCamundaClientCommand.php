<?php

namespace BeyondCRUD\LaravelCamundaClient\Commands;

use Illuminate\Console\Command;

class LaravelCamundaClientCommand extends Command
{
    public $signature = 'laravel-camunda-client';

    public function handle(): int
    {
        /** @var string */
        $string = config('camunda-client.url');
        $this->comment($string);

        return self::SUCCESS;
    }
}
