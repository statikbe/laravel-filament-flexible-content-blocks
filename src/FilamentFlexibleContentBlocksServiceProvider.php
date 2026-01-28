<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Statikbe\FilamentFlexibleContentBlocks\Commands\UpgradeSpatieImageFieldsCommand;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\CallToAction;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\Card;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\Hero;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\OverviewCard;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\VideoBackground;

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
                Card::class,
                VideoBackground::class,
            )
            ->hasMigrations(['create_default_pages_table', 'create_default_translatable_pages_table'])
            ->hasTranslations()
            ->hasCommand(UpgradeSpatieImageFieldsCommand::class);
    }

    public function packageBooted(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.$this->package->name.'.php', $this->package->name);

        $supportedLocales = config(
            $this->package->name.'.supported_locales',
            config('app.supported_locales', ['en'])
        );

        FilamentFlexibleContentBlocks::setLocales($supportedLocales);

        // Override default timeout to 120 seconds
        Config::set('openai.request_timeout', env('OPENAI_REQUEST_TIMEOUT', 120));
    }
}
