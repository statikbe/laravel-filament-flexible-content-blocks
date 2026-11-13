<?php

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
