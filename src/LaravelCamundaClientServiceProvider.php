<?php

namespace RapidKit\LaravelCamundaClient;

use RapidKit\LaravelCamundaClient\Commands\ConsumeExternalTaskCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCamundaClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-camunda-client')
            ->hasConfigFile()
            ->hasCommand(ConsumeExternalTaskCommand::class);
    }
}
