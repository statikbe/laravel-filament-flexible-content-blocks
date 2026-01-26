<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Storage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Test Helpers
|--------------------------------------------------------------------------
|
| Helper functions to reduce duplication in test setup
|
*/

/**
 * Set up fake storage for media uploads
 */
function setupFakeStorage(): void
{
    Storage::fake('public');
}

/**
 * Set up Filament panel and authenticate a test user
 */
function setupFilamentPanel(): void
{
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $user = \Orchestra\Testbench\Factories\UserFactory::new()->create();
    test()->actingAs($user);
}

/**
 * Configure default content blocks
 */
function setupDefaultContentBlocks(?array $blocks = null): void
{
    config([
        'filament-flexible-content-blocks.default_blocks' => $blocks ?? [
            TextImageBlock::class,
            ImageBlock::class,
            VideoBlock::class,
            QuoteBlock::class,
            CallToActionBlock::class,
            HtmlBlock::class,
        ],
    ]);
}

/**
 * Configure translatable content blocks with locales
 */
function setupTranslatableContentBlocks(?array $blocks = null, array $locales = ['en', 'nl'], string $defaultLocale = 'en'): void
{
    config([
        'filament-flexible-content-blocks.default_blocks' => $blocks ?? [
            TextImageBlock::class,
            ImageBlock::class,
        ],
        'filament-flexible-content-blocks.supported_locales' => $locales,
        'filament-flexible-content-blocks.default_locale' => $defaultLocale,
    ]);
}

/**
 * Set up view theme prefix
 */
function setupViewTheme(string $prefix = 'tailwind.'): void
{
    config([
        'filament-flexible-content-blocks.view_theme_prefix' => $prefix,
    ]);
}
