<?php

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;

it('generates a translated slug from the title on create', function () {
    $page = TranslatablePage::factory()->create([
        'title' => ['en' => 'Hello world', 'nl' => 'Hallo wereld'],
        'slug' => null,
    ]);

    expect($page->getTranslations('slug'))->toBe([
        'en' => 'hello-world',
        'nl' => 'hallo-wereld',
    ]);
});

it('fills a missing slug translation on update without overwriting existing ones', function () {
    $page = TranslatablePage::factory()->create([
        'title' => ['en' => 'Hello world'],
        'slug' => ['en' => 'custom-slug'],
    ]);

    $page->setTranslation('title', 'nl', 'Hallo wereld');
    $page->save();

    expect($page->fresh()->getTranslations('slug'))->toBe([
        'en' => 'custom-slug',
        'nl' => 'hallo-wereld',
    ]);
});

describe('route binding', function () {
    beforeEach(function () {
        Route::get('/translatable-pages/{page}', fn (TranslatablePage $page) => $page->getKey())
            ->middleware(SubstituteBindings::class);

        app()->setLocale('en');

        $this->page = TranslatablePage::factory()->create([
            'title' => ['en' => 'Hello world', 'nl' => 'Hallo wereld'],
            'slug' => ['en' => 'hello-world', 'nl' => 'hallo-wereld'],
        ]);
    });

    it('resolves a page by the slug of the current locale', function () {
        $this->get('/translatable-pages/hello-world')
            ->assertOk()
            ->assertSee((string) $this->page->getKey());
    });

    it('resolves a page by the slug of another supported locale', function () {
        $this->get('/translatable-pages/hallo-wereld')
            ->assertOk()
            ->assertSee((string) $this->page->getKey());
    });

    it('returns a 404 for an unknown slug', function () {
        $this->get('/translatable-pages/does-not-exist')->assertNotFound();
    });

    it('resolves via resolveRouteBinding for every supported locale', function () {
        $model = new TranslatablePage;

        expect($model->resolveRouteBinding('hello-world')?->is($this->page))->toBeTrue()
            ->and($model->resolveRouteBinding('hallo-wereld')?->is($this->page))->toBeTrue();
    });

    it('returns the raw translated slug as localized route key', function () {
        expect($this->page->getLocalizedRouteKey('en'))->toBe('hello-world')
            ->and($this->page->getLocalizedRouteKey('nl'))->toBe('hallo-wereld');
    });
});
