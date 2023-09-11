<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statikbe\FilamentFlexibleContentBlocks\Commands\CreateFlexibleContentBlocksModelCommand;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\CallToAction;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\Card;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\Hero;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\OverviewCard;

class FilamentFlexibleContentBlocksServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-flexible-content-blocks';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasViewComponents('flexible',
                ContentBlocks::class,
                Hero::class,
                OverviewCard::class,
                CallToAction::class,
                Card::class)
            ->hasMigrations(['create_default_pages_table', 'create_default_translatable_pages_table'])
            ->hasTranslations()
            ->hasCommand(CreateFlexibleContentBlocksModelCommand::class);
    }

    public function packageBooted(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.$this->package->name.'.php', $this->package->name);

        $supportedLocales = config(
            $this->package->name.'.supported_locales',
            config('app.supported_locales', ['en'])
        );

        FilamentFlexibleContentBlocks::setLocales($supportedLocales);
    }
}
