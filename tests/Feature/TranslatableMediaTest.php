<?php

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;

beforeEach(function () {
    setupFilamentPanel();
    setupTranslatableContentBlocks();
    setupFakeStorage();
});

it('stores media with locale in custom properties', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'media-locale-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
    ]);

    $blockId = BlockIdField::generateBlockId();

    // Add media with locale custom property
    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties([
            'block' => $blockId,
            'locale' => 'en',
        ])
        ->toMediaCollection(ImageBlock::getName());

    expect($media->getCustomProperty('locale'))->toBe('en')
        ->and($media->getCustomProperty('block'))->toBe($blockId);
});

it('each locale has independent media records', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'independent-media-test',
        'title' => ['en' => 'Test', 'nl' => 'Test'],
        'slug' => ['en' => 'test', 'nl' => 'test'],
    ]);

    $enBlockId = BlockIdField::generateBlockId();
    $nlBlockId = BlockIdField::generateBlockId();

    // Add EN media
    $enMedia = $page->addMedia(UploadedFile::fake()->image('en-image.jpg'))
        ->withCustomProperties([
            'block' => $enBlockId,
            'locale' => 'en',
        ])
        ->toMediaCollection(ImageBlock::getName());

    // Add NL media
    $nlMedia = $page->addMedia(UploadedFile::fake()->image('nl-image.jpg'))
        ->withCustomProperties([
            'block' => $nlBlockId,
            'locale' => 'nl',
        ])
        ->toMediaCollection(ImageBlock::getName());

    // Verify they are separate records
    expect($enMedia->id)->not()->toBe($nlMedia->id)
        ->and($enMedia->getCustomProperty('locale'))->toBe('en')
        ->and($nlMedia->getCustomProperty('locale'))->toBe('nl')
        ->and($page->getMedia(ImageBlock::getName()))->toHaveCount(2);
});

it('filters media by locale using custom properties', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'filter-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
    ]);

    $enBlockId = BlockIdField::generateBlockId();
    $nlBlockId = BlockIdField::generateBlockId();

    // Add media for different locales
    $page->addMedia(UploadedFile::fake()->image('en1.jpg'))
        ->withCustomProperties(['block' => $enBlockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $page->addMedia(UploadedFile::fake()->image('en2.jpg'))
        ->withCustomProperties(['block' => $enBlockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $page->addMedia(UploadedFile::fake()->image('nl1.jpg'))
        ->withCustomProperties(['block' => $nlBlockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    // Filter by EN locale
    $enMedia = $page->media()
        ->where('collection_name', ImageBlock::getName())
        ->where('custom_properties->locale', 'en')
        ->get();

    expect($enMedia)->toHaveCount(2);

    // Filter by NL locale
    $nlMedia = $page->media()
        ->where('collection_name', ImageBlock::getName())
        ->where('custom_properties->locale', 'nl')
        ->get();

    expect($nlMedia)->toHaveCount(1);
});

it('can upload different images per locale in content blocks', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'upload-per-locale-test',
        'title' => ['en' => 'English', 'nl' => 'Dutch'],
        'slug' => ['en' => 'english', 'nl' => 'dutch'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'image_title' => 'English Image',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'image_title' => 'Dutch Image',
                    ],
                ],
            ],
        ],
    ]);

    $enBlocks = $page->getTranslation('content_blocks', 'en');
    $enBlocks = is_string($enBlocks) ? json_decode($enBlocks, true) : $enBlocks;
    $enBlockId = $enBlocks[0]['data']['block_id'];

    $nlBlocks = $page->getTranslation('content_blocks', 'nl');
    $nlBlocks = is_string($nlBlocks) ? json_decode($nlBlocks, true) : $nlBlocks;
    $nlBlockId = $nlBlocks[0]['data']['block_id'];

    // Upload EN image
    $page->addMedia(UploadedFile::fake()->image('english.jpg'))
        ->withCustomProperties(['block' => $enBlockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    // Upload NL image
    $page->addMedia(UploadedFile::fake()->image('dutch.jpg'))
        ->withCustomProperties(['block' => $nlBlockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    // Verify EN media
    $enMedia = $page->getMedia(ImageBlock::getName())->filter(function ($media) use ($enBlockId) {
        return $media->getCustomProperty('block') === $enBlockId;
    });
    expect($enMedia)->toHaveCount(1)
        ->and($enMedia->first()->getCustomProperty('locale'))->toBe('en')
        ->and($enMedia->first()->file_name)->toBe('english.jpg');

    // Verify NL media
    $nlMedia = $page->getMedia(ImageBlock::getName())->filter(function ($media) use ($nlBlockId) {
        return $media->getCustomProperty('block') === $nlBlockId;
    });
    expect($nlMedia)->toHaveCount(1)
        ->and($nlMedia->first()->getCustomProperty('locale'))->toBe('nl')
        ->and($nlMedia->first()->file_name)->toBe('dutch.jpg');
});

it('switching locales shows correct media per locale', function () {
    $enBlockId = BlockIdField::generateBlockId();
    $nlBlockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'switch-locale-test',
        'title' => ['en' => 'English', 'nl' => 'Dutch'],
        'slug' => ['en' => 'english', 'nl' => 'dutch'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $enBlockId,
                        'image_title' => 'EN Image',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $nlBlockId,
                        'image_title' => 'NL Image',
                    ],
                ],
            ],
        ],
    ]);

    // Add images for both locales
    $page->addMedia(UploadedFile::fake()->image('en-pic.jpg'))
        ->withCustomProperties(['block' => $enBlockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $page->addMedia(UploadedFile::fake()->image('nl-pic.jpg'))
        ->withCustomProperties(['block' => $nlBlockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    // In EN locale, should only see EN media
    $enLocaleMedia = $page->media()
        ->where('collection_name', ImageBlock::getName())
        ->where('custom_properties->locale', 'en')
        ->get();

    expect($enLocaleMedia)->toHaveCount(1)
        ->and($enLocaleMedia[0]->file_name)->toBe('en-pic.jpg');

    // In NL locale, should only see NL media
    $nlLocaleMedia = $page->media()
        ->where('collection_name', ImageBlock::getName())
        ->where('custom_properties->locale', 'nl')
        ->get();

    expect($nlLocaleMedia)->toHaveCount(1)
        ->and($nlLocaleMedia[0]->file_name)->toBe('nl-pic.jpg');
});

it('deleting media in one locale does not affect other locales', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'delete-locale-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
    ]);

    $enBlockId = BlockIdField::generateBlockId();
    $nlBlockId = BlockIdField::generateBlockId();

    // Add media for both locales
    $enMedia = $page->addMedia(UploadedFile::fake()->image('en.jpg'))
        ->withCustomProperties(['block' => $enBlockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $nlMedia = $page->addMedia(UploadedFile::fake()->image('nl.jpg'))
        ->withCustomProperties(['block' => $nlBlockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    expect($page->getMedia(ImageBlock::getName()))->toHaveCount(2);

    // Delete EN media
    $enMedia->delete();

    // NL media should still exist
    $remainingMedia = $page->fresh()->getMedia(ImageBlock::getName());

    expect($remainingMedia)->toHaveCount(1)
        ->and($remainingMedia[0]->id)->toBe($nlMedia->id)
        ->and($remainingMedia[0]->getCustomProperty('locale'))->toBe('nl');
});

it('supports translatable media collections', function () {
    $page = TranslatablePage::factory()->create([
        'code' => 'translatable-collection-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
    ]);

    // Mark collection as translatable (if trait is used)
    if (method_exists($page, 'mergeTranslatableMediaCollection')) {
        $page->mergeTranslatableMediaCollection([ImageBlock::getName()]);
    }

    $blockId = BlockIdField::generateBlockId();

    // Add media with locale
    $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties(['block' => $blockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $page->addMedia(UploadedFile::fake()->image('test2.jpg'))
        ->withCustomProperties(['block' => $blockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    // Get translatable media UUIDs if method exists
    if (method_exists($page, 'getTranslatableMediaUuidsPerMediaCollection')) {
        $enUuids = $page->getTranslatableMediaUuidsPerMediaCollection('en');
        $nlUuids = $page->getTranslatableMediaUuidsPerMediaCollection('nl');

        expect($enUuids)->toBeArray();
        expect($nlUuids)->toBeArray();
    } else {
        // Just verify media exists per locale
        $enMedia = $page->media()->where('custom_properties->locale', 'en')->get();
        $nlMedia = $page->media()->where('custom_properties->locale', 'nl')->get();

        expect($enMedia)->toHaveCount(1);
        expect($nlMedia)->toHaveCount(1);
    }
});

it('media remains associated with correct block and locale after edit', function () {
    $blockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'edit-persist-test',
        'title' => ['en' => 'Test', 'nl' => 'Test'],
        'slug' => ['en' => 'test', 'nl' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $blockId,
                        'image_title' => 'Test Image',
                    ],
                ],
            ],
            'nl' => [],
        ],
    ]);

    // Add media to EN block
    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties(['block' => $blockId, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    $originalMediaId = $media->id;

    // Update page title (simulating an edit)
    $page->setTranslation('title', 'en', 'Updated Title');
    $page->save();

    // Reload page and verify media still exists with correct properties
    $page->refresh();

    $persistedMedia = Media::find($originalMediaId);

    expect($persistedMedia)->not()->toBeNull()
        ->and($persistedMedia->getCustomProperty('block'))->toBe($blockId)
        ->and($persistedMedia->getCustomProperty('locale'))->toBe('en')
        ->and($persistedMedia->model_id)->toBe($page->id);
});

it('can have empty media in one locale and filled in another', function () {
    $nlBlockId = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'empty-media-test',
        'title' => ['en' => 'English', 'nl' => 'Dutch'],
        'slug' => ['en' => 'english', 'nl' => 'dutch'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => BlockIdField::generateBlockId(),
                        'image_title' => 'EN Block (no image)',
                    ],
                ],
            ],
            'nl' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $nlBlockId,
                        'image_title' => 'NL Block (with image)',
                    ],
                ],
            ],
        ],
    ]);

    // Only add media to NL locale
    $page->addMedia(UploadedFile::fake()->image('nl-only.jpg'))
        ->withCustomProperties(['block' => $nlBlockId, 'locale' => 'nl'])
        ->toMediaCollection(ImageBlock::getName());

    // Verify EN has no media
    $enMedia = $page->media()
        ->where('custom_properties->locale', 'en')
        ->get();

    expect($enMedia)->toBeEmpty();

    // Verify NL has media
    $nlMedia = $page->media()
        ->where('custom_properties->locale', 'nl')
        ->get();

    expect($nlMedia)->toHaveCount(1)
        ->and($nlMedia[0]->file_name)->toBe('nl-only.jpg');
});

it('multiple blocks in same locale each have independent media', function () {
    $block1Id = BlockIdField::generateBlockId();
    $block2Id = BlockIdField::generateBlockId();

    $page = TranslatablePage::factory()->create([
        'code' => 'multi-block-locale-test',
        'title' => ['en' => 'Test'],
        'slug' => ['en' => 'test'],
        'content_blocks' => [
            'en' => [
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $block1Id,
                        'image_title' => 'First Block',
                    ],
                ],
                [
                    'type' => 'image',
                    'data' => [
                        'block_id' => $block2Id,
                        'image_title' => 'Second Block',
                    ],
                ],
            ],
        ],
    ]);

    // Add media to first block
    $page->addMedia(UploadedFile::fake()->image('block1.jpg'))
        ->withCustomProperties(['block' => $block1Id, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    // Add media to second block
    $page->addMedia(UploadedFile::fake()->image('block2.jpg'))
        ->withCustomProperties(['block' => $block2Id, 'locale' => 'en'])
        ->toMediaCollection(ImageBlock::getName());

    // Verify block 1 media
    $block1Media = $page->getMedia(ImageBlock::getName())->filter(function ($media) use ($block1Id) {
        return $media->getCustomProperty('block') === $block1Id;
    });
    expect($block1Media)->toHaveCount(1)
        ->and($block1Media->first()->file_name)->toBe('block1.jpg')
        ->and($block1Media->first()->getCustomProperty('locale'))->toBe('en');

    // Verify block 2 media
    $block2Media = $page->getMedia(ImageBlock::getName())->filter(function ($media) use ($block2Id) {
        return $media->getCustomProperty('block') === $block2Id;
    });
    expect($block2Media)->toHaveCount(1)
        ->and($block2Media->first()->file_name)->toBe('block2.jpg')
        ->and($block2Media->first()->getCustomProperty('locale'))->toBe('en');
});
