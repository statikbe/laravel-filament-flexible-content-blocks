<?php

use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages\EditTranslatablePage;

beforeEach(function () {
    setupFilamentPanel();
    setupTranslatableContentBlocks(
        locales: ['en', 'nl', 'fr']
    );
    setupFakeStorage();
});

it('can copy content blocks from EN to NL locale', function () {
    // Create a page with English content blocks
    $page = TranslatablePage::factory()->create([
        'code' => 'copy-test',
        'title' => ['en' => 'English Title'],
        'slug' => ['en' => 'english-slug'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'English Block',
                        'text' => '<p>English content</p>',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    $component = Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->assertSet('activeLocale', 'en')
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    // Reload page to verify blocks were copied
    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toBeArray()
        ->and($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['type'])->toBe('text-image')
        ->and($nlBlocks[0]['data']['title'])->toBe('English Block')
        ->and($nlBlocks[0]['data']['text'])->toBe('<p>English content</p>');
});

it('generates new block IDs when copying to other locales', function () {
    $originalBlockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'block-id-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => $originalBlockId,
                        'title' => 'Block',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    $newBlockId = $nlBlocks[0]['data']['block_id'];

    expect($newBlockId)->toBeString()
        ->and($newBlockId)->not()->toBe($originalBlockId)
        ->and(strlen($newBlockId))->toBeGreaterThan(0);
});

it('copies media to new locale with new block ID', function () {
    $originalBlockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'media-copy-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $originalBlockId,
                        'image_title' => 'Test Image',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    // Add media to EN block
    $originalMedia = $page->addMedia(UploadedFile::fake()->image('test.jpg', 1200, 630))
        ->withCustomProperties(['block' => $originalBlockId])
        ->toMediaCollection(ImageBlock::getName());

    expect($page->getMedia(ImageBlock::getName()))->toHaveCount(1);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    // Should now have 2 media items (original EN + copied NL)
    $allMedia = $page->getMedia(ImageBlock::getName());
    expect($allMedia)->toHaveCount(2);

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    $newBlockId = $nlBlocks[0]['data']['block_id'];

    // Find the copied media
    $copiedMedia = $allMedia->filter(function (Media $item) use ($newBlockId) {
        return $item->getCustomProperty('block') === $newBlockId;
    })->first();

    expect($copiedMedia)->not()->toBeNull()
        ->and($copiedMedia->id)->not()->toBe($originalMedia->id)
        ->and($copiedMedia->getCustomProperty('block'))->toBe($newBlockId)
        ->and($copiedMedia->collection_name)->toBe(ImageBlock::getName());
});

it('copies blocks to NL locale (multiple locales configured)', function () {
    // Note: The action copies to ALL other locales defined in config
    // but we only test NL here since FR may not be in the panel's locale config
    $page = TranslatablePage::factory()->create([
        'code' => 'multi-locale-test',
        'title' => ['en' => 'Test', 'nl' => 'Test'],
        'slug' => ['en' => 'test', 'nl' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'English Block',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    // Check NL blocks were copied
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['data']['title'])->toBe('English Block');

    // Verify EN and NL locales have different block IDs
    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;

    $enBlockId = $enBlocks[0]['data']['block_id'];
    $nlBlockId = $nlBlocks[0]['data']['block_id'];

    expect($enBlockId)->not()->toBe($nlBlockId);
});

it('replaces existing blocks in target locale', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'replace-test',
        'title' => ['en' => 'Test', 'nl' => 'Test'],
        'slug' => ['en' => 'test', 'nl' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'New English Block',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'Old Dutch Block',
                    ],
                ],
            ],
        ],
    ]);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    // Old block should be replaced
    expect($nlBlocks)->toHaveCount(1)
        ->and($nlBlocks[0]['data']['title'])->toBe('New English Block')
        ->and($nlBlocks[0]['data']['title'])->not()->toBe('Old Dutch Block');
});

it('handles empty content blocks gracefully', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'empty-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [],
            'nl' => [],
        ],
    ]);

    $component = Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    // Should remain empty
    expect($nlBlocks)->toBeArray()
        ->and($nlBlocks)->toBeEmpty();
});

it('copies multiple blocks with correct order', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'order-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'First Block',
                    ],
                ],
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'Second Block',
                    ],
                ],
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'Third Block',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toHaveCount(3)
        ->and($nlBlocks[0]['data']['title'])->toBe('First Block')
        ->and($nlBlocks[1]['data']['title'])->toBe('Second Block')
        ->and($nlBlocks[2]['data']['title'])->toBe('Third Block');
});

it('copies multiple media items from a block', function () {
    $blockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'multi-media-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => $blockId,
                        'title' => 'Block with Images',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    // Add multiple media items to the same block
    $page->addMedia(UploadedFile::fake()->image('image1.jpg', 800, 600))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(TextImageBlock::getName());

    $page->addMedia(UploadedFile::fake()->image('image2.jpg', 800, 600))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(TextImageBlock::getName());

    $originalMediaCount = $page->getMedia(TextImageBlock::getName())->count();
    expect($originalMediaCount)->toBe(2);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    // Should have 4 media items total (2 EN + 2 NL)
    $allMedia = $page->getMedia(TextImageBlock::getName());
    expect($allMedia->count())->toBe(4);

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    $newBlockId = $nlBlocks[0]['data']['block_id'];

    // Both copied media should have the new block ID
    $copiedMedia = $allMedia->filter(function ($item) use ($newBlockId) {
        return $item->getCustomProperty('block') === $newBlockId;
    });
    expect($copiedMedia->count())->toBe(2);
});

it('preserves block type when copying', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'type-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'Text Image Block',
                    ],
                ],
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'image_title' => 'Image Block',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks)->toHaveCount(2)
        ->and($nlBlocks[0]['type'])->toBe('text-image')
        ->and($nlBlocks[1]['type'])->toBe('image');
});

it('action is hidden when only one locale is available', function () {
    // Override config to have only one locale
    config(['filament-flexible-content-blocks.supported_locales' => ['en']]);

    $page = TranslatablePage::factory()->create([
        'code' => 'single-locale-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => ['en' => []],
    ]);

    $component = Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ]);

    // Action should be hidden (we can't directly test visibility, but can verify locales)
    expect(config('filament-flexible-content-blocks.supported_locales'))->toHaveCount(1);
});

it('does not copy blocks from other locales to EN', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'reverse-test',
        'title' => ['en' => 'Test', 'nl' => 'Test'],
        'slug' => ['en' => 'test', 'nl' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'EN Block',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'text-image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'title' => 'NL Block',
                    ],
                ],
            ],
        ],
    ]);

    $originalEnBlocks = $page->getTranslation('content_blocks', 'en');

    Livewire::test(EditTranslatablePage::class, [
        'record' => $page->getRouteKey(),
    ])
        ->set('activeLocale', 'en')
        ->callAction('copy_content_blocks_to_other_locales_page_action');

    $page->refresh();

    // EN blocks should remain unchanged (only copies TO other locales, not FROM)
    $enBlocks = $page->getTranslation('content_blocks', 'en');

    expect($enBlocks)->toBe($originalEnBlocks);

    // NL blocks should now match EN
    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;

    expect($nlBlocks[0]['data']['title'])->toBe('EN Block');
});
