<?php

namespace BeyondCRUD\LaravelCamundaClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BeyondCRUD\LaravelCamundaClient\Commands\LaravelCamundaClientCommand;

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
