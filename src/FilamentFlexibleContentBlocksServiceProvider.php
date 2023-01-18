<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Statikbe\FilamentFlexibleContentBlocks\Commands\CreateFlexibleContentBlocksModelCommand;

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
            ->name('filament-flexible-content-blocks')
            ->hasConfigFile()
            ->hasViews()
            //->hasViewComponents('flexible-block')
            ->hasMigrations(['create_default_pages_table', 'create_default_translatable_pages_table'])
            ->hasCommand(CreateFlexibleContentBlocksModelCommand::class);
    }
}
