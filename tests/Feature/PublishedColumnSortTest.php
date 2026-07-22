<?php

use Livewire\Livewire;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\PageResource\Pages\ListPages;

beforeEach(function () {
    setupFilamentPanel();
});

it('can sort the published column without a database error', function () {
    Page::factory()->published()->create();
    Page::factory()->unpublished()->create();

    Livewire::test(ListPages::class)
        ->sortTable('is_published')
        ->assertOk();
});

it('sorts unpublished pages before published pages ascending', function () {
    $published = Page::factory()->published()->create();
    $unpublished = Page::factory()->unpublished()->create();

    Livewire::test(ListPages::class)
        ->sortTable('is_published')
        ->assertCanSeeTableRecords([$unpublished, $published], inOrder: true);
});

it('sorts published pages before unpublished pages descending', function () {
    $published = Page::factory()->published()->create();
    $unpublished = Page::factory()->unpublished()->create();

    Livewire::test(ListPages::class)
        ->sortTable('is_published', 'desc')
        ->assertCanSeeTableRecords([$published, $unpublished], inOrder: true);
});

it('treats a page without publishing dates as published when sorting', function () {
    $alwaysPublished = Page::factory()->create([
        'publishing_begins_at' => null,
        'publishing_ends_at' => null,
    ]);
    $unpublished = Page::factory()->unpublished()->create();

    Livewire::test(ListPages::class)
        ->sortTable('is_published', 'desc')
        ->assertCanSeeTableRecords([$alwaysPublished, $unpublished], inOrder: true);
});
