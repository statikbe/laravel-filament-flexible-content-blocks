<?php

use Livewire\Livewire;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages\CreateTranslatablePage;

beforeEach(function () {
    setupFilamentPanel();
    setupTranslatableContentBlocks([
        TextImageBlock::class,
        VideoBlock::class,
        QuoteBlock::class,
    ]);
});

it('can create translatable page with content blocks in default locale', function () {
    Livewire::test(CreateTranslatablePage::class)
        ->fillForm([
            'title' => 'English Title',
            'slug' => 'english-slug',
            'code' => 'test-page',
            'content_blocks' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'title' => 'English Block Title',
                        'text' => '<p>English content</p>',
                    ],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = TranslatablePage::where('code', 'test-page')->first();
    expect($page)->not()->toBeNull();

    // Verify English content
    expect($page->getTranslation('title', 'en'))->toBe('English Title');

    $contentBlocks = $page->getTranslation('content_blocks', 'en');
    $contentBlocks = is_string($contentBlocks) ? json_decode($contentBlocks, true) : $contentBlocks;

    expect($contentBlocks)->toBeArray()
        ->and($contentBlocks)->toHaveCount(1)
        ->and($contentBlocks[0]['type'])->toBe('text-image')
        ->and($contentBlocks[0]['data']['title'])->toBe('English Block Title');
});

it('can save different content blocks per locale', function () {
    // Create a page with English content
    $pageId = DB::table('translatable_pages')->insertGetId([
        'title' => json_encode(['en' => 'English Title', 'nl' => 'Dutch Title']),
        'slug' => json_encode(['en' => 'english-slug', 'nl' => 'dutch-slug']),
        'code' => 'multilingual-page',
        'content_blocks' => json_encode([
            'en' => [
                ['type' => 'text-image', 'data' => ['title' => 'English Block']],
            ],
            'nl' => [
                ['type' => 'video', 'data' => ['title' => 'Dutch Block']],
            ],
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $page = TranslatablePage::find($pageId);

    // Verify English content blocks
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;

    expect($enBlocks)->toBeArray()
        ->and($enBlocks)->toHaveCount(1)
        ->and($enBlocks[0]['type'])->toBe('text-image')
        ->and($enBlocks[0]['data']['title'])->toBe('English Block');

    // Verify Dutch content blocks are different
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toBeArray()
        ->and($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['type'])->toBe('video')
        ->and($nlBlocks[0]['data']['title'])->toBe('Dutch Block');
});

it('ensures locales maintain independent content blocks', function () {
    // Create a page with content in both locales
    $pageId = DB::table('translatable_pages')->insertGetId([
        'title' => json_encode(['en' => 'English', 'nl' => 'Dutch']),
        'slug' => json_encode(['en' => 'english', 'nl' => 'dutch']),
        'code' => 'locale-independence-test',
        'content_blocks' => json_encode([
            'en' => [
                ['type' => 'text-image', 'data' => ['title' => 'English Content']],
                ['type' => 'video', 'data' => ['title' => 'English Video']],
            ],
            'nl' => [
                ['type' => 'quote', 'data' => ['quote' => 'Dutch Quote']],
            ],
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $page = TranslatablePage::find($pageId);

    // Verify each locale has its own independent content
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;

    expect($enBlocks)->toHaveCount(2)
        ->and($enBlocks[0]['type'])->toBe('text-image')
        ->and($enBlocks[1]['type'])->toBe('video');

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['type'])->toBe('quote');

    // Verify they're truly independent (different counts and types)
    expect(count($enBlocks))->not()->toBe(count($nlBlocks))
        ->and($enBlocks[0]['type'])->not()->toBe($nlBlocks[0]['type']);
});

it('can create page with empty content blocks in one locale and filled in another', function () {
    $pageId = DB::table('translatable_pages')->insertGetId([
        'title' => json_encode(['en' => 'English', 'nl' => 'Dutch']),
        'slug' => json_encode(['en' => 'english', 'nl' => 'dutch']),
        'code' => 'empty-test',
        'content_blocks' => json_encode([
            'en' => [],
            'nl' => [
                ['type' => 'text-image', 'data' => ['title' => 'Dutch Content']],
            ],
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $page = TranslatablePage::find($pageId);

    // Verify English blocks are empty
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;
    expect($enBlocks)->toBeArray()->and($enBlocks)->toBeEmpty();

    // Verify Dutch blocks have content
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['data']['title'])->toBe('Dutch Content');
});

it('can render translatable content blocks field in create page', function () {
    $component = Livewire::test(CreateTranslatablePage::class);

    $component->assertFormExists();
    $component->assertFormFieldExists('content_blocks');
});

it('can switch locales in create form and fill different content blocks per locale via Livewire', function () {
    $component = Livewire::test(CreateTranslatablePage::class)
        ->assertSet('activeLocale', 'en') // Verify default locale is English
        ->fillForm([
            'code' => 'multilingual-test',
            'title' => 'English Title',
            'slug' => 'english-slug',
            'content_blocks' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'title' => 'English Block',
                        'text' => '<p>English content</p>',
                    ],
                ],
            ],
        ])
        // Switch to Dutch locale using Livewire
        ->set('activeLocale', 'nl')
        ->assertSet('activeLocale', 'nl') // Verify locale switched
        ->fillForm([
            'title' => 'Dutch Title',
            'slug' => 'dutch-slug',
            'content_blocks' => [
                [
                    'type' => 'video',
                    'data' => [
                        'title' => 'Dutch Block',
                        'video_url' => 'https://youtube.com/watch?v=test',
                    ],
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = TranslatablePage::where('code', 'multilingual-test')->first();
    expect($page)->not()->toBeNull();

    // Verify English content blocks were saved correctly
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;
    expect($enBlocks)->toBeArray()
        ->and($enBlocks)->toHaveCount(1)
        ->and($enBlocks[0]['type'])->toBe('text-image')
        ->and($enBlocks[0]['data']['title'])->toBe('English Block');

    // Verify Dutch content blocks were saved correctly and are different
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    expect($nlBlocks)->toBeArray()
        ->and($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['type'])->toBe('video')
        ->and($nlBlocks[0]['data']['title'])->toBe('Dutch Block');
});

it('maintains separate content blocks when switching locales multiple times via Livewire', function () {
    $component = Livewire::test(CreateTranslatablePage::class)
        // Fill English with 2 blocks
        ->fillForm([
            'code' => 'multi-switch-test',
            'title' => 'English',
            'slug' => 'english',
            'content_blocks' => [
                ['type' => 'text-image', 'data' => ['title' => 'EN Block 1']],
                ['type' => 'quote', 'data' => ['quote' => 'EN Block 2']],
            ],
        ])
        // Switch to Dutch
        ->set('activeLocale', 'nl')
        ->fillForm([
            'title' => 'Dutch',
            'slug' => 'dutch',
            'content_blocks' => [
                ['type' => 'video', 'data' => ['title' => 'NL Block 1']],
            ],
        ])
        // Switch back to English - should still have 2 English blocks
        ->set('activeLocale', 'en')
        ->assertFormSet([
            'title' => 'English', // Title should be English again
        ])
        // Switch to Dutch again - should still have 1 Dutch block
        ->set('activeLocale', 'nl')
        ->assertFormSet([
            'title' => 'Dutch', // Title should be Dutch
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = TranslatablePage::where('code', 'multi-switch-test')->first();

    // English should have 2 blocks (as originally set)
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;
    expect($enBlocks)->toHaveCount(2)
        ->and($enBlocks[0]['type'])->toBe('text-image')
        ->and($enBlocks[1]['type'])->toBe('quote');

    // Dutch should have 1 block
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['type'])->toBe('video');
});

it('preserves content blocks structure when saving with multiple locales', function () {
    // Test with complex nested data
    $pageId = DB::table('translatable_pages')->insertGetId([
        'title' => json_encode(['en' => 'Test', 'nl' => 'Test']),
        'slug' => json_encode(['en' => 'test', 'nl' => 'test']),
        'code' => 'complex-test',
        'content_blocks' => json_encode([
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'title' => 'English Title',
                        'text' => '<p>HTML <strong>content</strong></p>',
                        'image_position' => 'left',
                    ],
                ],
                [
                    'type' => 'quote',
                    'data' => [
                        'quote' => 'Quote with "special" & characters',
                        'author' => 'Author Name',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'video',
                    'data' => [
                        'title' => 'Dutch Video',
                        'video_url' => 'https://youtube.com/watch?v=test',
                    ],
                ],
            ],
        ]),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $page = TranslatablePage::find($pageId);

    // Verify data integrity for English
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;

    expect($enBlocks)->toHaveCount(2)
        ->and($enBlocks[0]['data']['text'])->toBe('<p>HTML <strong>content</strong></p>')
        ->and($enBlocks[1]['data']['quote'])->toBe('Quote with "special" & characters');

    // Verify data integrity for Dutch
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['data']['video_url'])->toBe('https://youtube.com/watch?v=test');
});
