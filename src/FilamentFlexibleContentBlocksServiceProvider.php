<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statikbe\FilamentFlexibleContentBlocks\Commands\FilamentFlexibleContentBlocksCommand;

class FilamentFlexibleContentBlocksServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-filament-flexible-content-blocks')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-filament-flexible-content-blocks_table')
            ->hasCommand(FilamentFlexibleContentBlocksCommand::class);
    }
}
