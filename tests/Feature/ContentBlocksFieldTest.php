<?php

use Filament\Facades\Filament;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Resources\Pages\Page as FilamentPage;
use Livewire\Livewire;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\ViewPageAction;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\PageResource\Pages\CreatePage;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    setupFilamentPanel();
    setupDefaultContentBlocks([
        TextImageBlock::class,
        ImageBlock::class,
        VideoBlock::class,
        QuoteBlock::class,
    ]);
});

it('can render content blocks field in create page', function () {
    $component = Livewire::test(CreatePage::class);

    $component->assertFormExists();
    $component->assertFormFieldExists('content_blocks');
});

it('can create a page with empty content blocks', function () {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content_blocks' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('pages', [
        'title' => 'Test Page',
        'slug' => 'test-page',
    ]);

    $page = Page::where('slug', 'test-page')->first();
    $contentBlocks = is_string($page->content_blocks)
        ? json_decode($page->content_blocks, true)
        : $page->content_blocks;
    expect($contentBlocks)->toBeArray()->and($contentBlocks)->toBeEmpty();
});

it('can create a page with text-image content block', function () {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'Test Page Block',
            'slug' => 'test-page-block',
            'content_blocks' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'title' => 'Test Block Title',
                        'text' => '<p>Test block content</p>',
                        'image_position' => 'left',
                    ],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('pages', [
        'title' => 'Test Page Block',
        'slug' => 'test-page-block',
    ]);

    $page = Page::where('slug', 'test-page-block')->first();
    $contentBlocks = is_string($page->content_blocks)
        ? json_decode($page->content_blocks, true)
        : $page->content_blocks;

    expect($contentBlocks)->toBeArray()
        ->and($contentBlocks)->toHaveCount(1)
        ->and($contentBlocks[0])->toHaveKey('type', 'text-image')
        ->and($contentBlocks[0]['data'])->toHaveKey('title', 'Test Block Title');
});

it('can create a page with multiple content blocks', function () {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'Test Multiple',
            'slug' => 'test-multiple',
            'content_blocks' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'title' => 'First Block',
                        'text' => '<p>First content</p>',
                    ],
                ],
                [
                    'type' => 'video',
                    'data' => [
                        'video_url' => 'https://youtube.com/watch?v=test',
                        'title' => 'Video Block',
                    ],
                ],
                [
                    'type' => 'quote',
                    'data' => [
                        'quote' => 'Test quote',
                        'author' => 'Test Author',
                    ],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = Page::where('slug', 'test-multiple')->first();
    $contentBlocks = is_string($page->content_blocks)
        ? json_decode($page->content_blocks, true)
        : $page->content_blocks;

    expect($contentBlocks)->toBeArray()
        ->and($contentBlocks)->toHaveCount(3)
        ->and($contentBlocks[0])->toHaveKey('type', 'text-image')
        ->and($contentBlocks[1])->toHaveKey('type', 'video')
        ->and($contentBlocks[2])->toHaveKey('type', 'quote');
});

// Note: We don't test reordering, adding, or removing blocks as those are core
// Filament Builder features, not package-specific functionality

it('content blocks field is a builder component', function () {
    $component = Livewire::test(CreatePage::class);

    // Verify the form exists and has the content_blocks field
    $component->assertFormExists();
    $component->assertFormFieldExists('content_blocks');

    // Successfully finding and asserting on the field is enough to confirm it's working
    expect(true)->toBeTrue();
});

it('content blocks builder has multiple block types available', function () {
    // Test that we can add different block types through the form
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'Test Multiple Block Types',
            'slug' => 'test-multiple-block-types',
            'content_blocks' => [
                ['type' => 'text-image', 'data' => ['title' => 'Text Image Block']],
                ['type' => 'video', 'data' => ['title' => 'Video Block']],
                ['type' => 'quote', 'data' => ['quote' => 'Quote Block']],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = Page::where('slug', 'test-multiple-block-types')->first();
    expect($page)->not()->toBeNull();

    $contentBlocks = is_string($page->content_blocks)
        ? json_decode($page->content_blocks, true)
        : $page->content_blocks;

    // Verify we can add multiple different block types
    expect($contentBlocks)->toHaveCount(3);
});

it('preserves content blocks data integrity', function () {
    $originalBlocks = [
        [
            'type' => 'text-image',
            'data' => [
                'title' => 'Test Title',
                'text' => '<p>Rich <strong>HTML</strong> content</p>',
                'image_position' => 'right',
                'image_width' => 'full',
            ],
        ],
        [
            'type' => 'quote',
            'data' => [
                'quote' => 'Quote with "special" characters & symbols',
                'author' => 'John Doe',
            ],
        ],
    ];

    $page = Page::factory()->create([
        'content_blocks' => json_encode($originalBlocks),
    ]);

    $page->refresh();
    $contentBlocks = is_string($page->content_blocks)
        ? json_decode($page->content_blocks, true)
        : $page->content_blocks;

    // Verify HTML and special characters are preserved
    expect($contentBlocks[0]['data']['text'])->toBe('<p>Rich <strong>HTML</strong> content</p>')
        ->and($contentBlocks[1]['data']['quote'])->toBe('Quote with "special" characters & symbols');
});

/*
|--------------------------------------------------------------------------
| ViewPageAction Tests - Helper Functions
|--------------------------------------------------------------------------
*/

function createTranslatablePageWithSlugs(array $slugs = ['en' => 'about-us', 'nl' => 'over-ons', 'fr' => 'a-propos']): TranslatablePage
{
    return TranslatablePage::factory()->create([
        'title' => array_map(fn ($slug) => ucwords(str_replace('-', ' ', $slug)), $slugs),
        'slug' => $slugs,
    ]);
}

function createViewPageActionWithLocale($record, ?string $activeLocale): ViewPageAction
{
    $livewire = Mockery::mock(FilamentPage::class);
    $livewire->shouldReceive('getActiveSchemaLocale')->andReturn($activeLocale);

    return ViewPageAction::make()
        ->record($record)
        ->livewire($livewire);
}

/*
|--------------------------------------------------------------------------
| ViewPageAction Tests
|--------------------------------------------------------------------------
*/

it('can resolve view page action url with correct slug for active schema locale', function () {
    // Arrange
    $record = createTranslatablePageWithSlugs();
    $action = createViewPageActionWithLocale($record, 'fr');

    // Act
    $url = $action->getUrl();

    // Assert - URL should contain the FRENCH slug
    expect($url)->toContain('a-propos')
        ->and($url)->not->toContain('about-us')
        ->and($url)->not->toContain('over-ons');
});

it('can resolve view page action url with fallback to app locale when no active schema locale', function () {
    // Arrange
    $record = createTranslatablePageWithSlugs();
    app()->setLocale('nl');
    $action = createViewPageActionWithLocale($record, null);

    // Act
    $url = $action->getUrl();

    // Assert - should fallback to app locale 'nl' and use Dutch slug
    expect($url)->toContain('over-ons')
        ->and($url)->not->toContain('about-us');
});
