<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Spatie\Translatable\TranslatableServiceProvider;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocksServiceProvider;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Providers\TestPanelProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        // Clear compiled views to prevent stale cached views from other branches:
        $viewsPath = __DIR__.'/../vendor/orchestra/testbench-core/laravel/storage/framework/views';
        if (is_dir($viewsPath)) {
            array_map('unlink', glob("{$viewsPath}/*.php"));
        }

        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Statikbe\\FilamentFlexibleContentBlocks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            MediaLibraryServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            TranslatableServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentFlexibleContentBlocksServiceProvider::class,
            TestPanelProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Set up Filament config
        config()->set('filament-flexible-content-blocks.supported_locales', ['en', 'nl']);
        config()->set('filament-flexible-content-blocks.default_locale', 'en');
        config()->set('filament-flexible-content-blocks.author_model', \Statikbe\FilamentFlexibleContentBlocks\Tests\Models\User::class);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
