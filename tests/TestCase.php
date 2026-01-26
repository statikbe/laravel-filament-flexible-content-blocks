<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
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
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\User;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Providers\TestPanelProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Statikbe\\FilamentFlexibleContentBlocks\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            // Sorted alphabetically by full namespace for Filament v4 compatibility
            // See: https://github.com/filamentphp/filament/discussions/17917
            BladeHeroiconsServiceProvider::class,        // BladeUI\Heroicons\...
            BladeIconsServiceProvider::class,            // BladeUI\Icons\...
            ActionsServiceProvider::class,               // Filament\Actions\...
            FilamentServiceProvider::class,              // Filament\...
            FormsServiceProvider::class,                 // Filament\Forms\...
            SchemasServiceProvider::class,               // Filament\Schemas\...
            SupportServiceProvider::class,               // Filament\Support\...
            TablesServiceProvider::class,                // Filament\Tables\...
            WidgetsServiceProvider::class,               // Filament\Widgets\...
            LivewireServiceProvider::class,              // Livewire\...
            BladeCaptureDirectiveServiceProvider::class, // RyanChandler\...
            MediaLibraryServiceProvider::class,          // Spatie\MediaLibrary\...
            TranslatableServiceProvider::class,          // Spatie\Translatable\...
            FilamentFlexibleContentBlocksServiceProvider::class, // Statikbe\...\...ServiceProvider
            TestPanelProvider::class,                    // Statikbe\...\...PanelProvider
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
        config()->set('filament-flexible-content-blocks.author_model', User::class);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}
