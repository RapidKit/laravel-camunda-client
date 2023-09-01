<?php

namespace BeyondCRUD\LaravelCamundaClient;

use BeyondCRUD\LaravelCamundaClient\Commands\LaravelCamundaClientCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelCamundaClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-camunda-client')
            ->hasConfigFile()
            ->hasCommand(LaravelCamundaClientCommand::class);
    }
}
