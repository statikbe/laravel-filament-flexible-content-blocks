<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;

beforeEach(function () {
    setupDefaultContentBlocks([
        TextImageBlock::class,
        ImageBlock::class,
    ]);
    setupFakeStorage();
});

it('can create page with media in content block', function () {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'content_blocks' => [],
    ]);

    // Generate a block ID for the image block
    $blockId = BlockIdField::generateBlockId();

    // Create a fake image file
    $file = UploadedFile::fake()->image('test-image.jpg', 1200, 630);

    // Add media to the page with block ID as custom property
    $media = $page->addMedia($file)
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    expect($media)->toBeInstanceOf(Media::class)
        ->and($media->collection_name)->toBe(ImageBlock::getName())
        ->and($media->getCustomProperty('block'))->toBe($blockId);

    // Verify the media file was stored
    Storage::disk('public')->assertExists($media->id.'/'.$media->file_name);
});

it('associates media with correct block via UUID', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $block1Id = BlockIdField::generateBlockId();
    $block2Id = BlockIdField::generateBlockId();

    // Upload two images to the same collection but different blocks
    $media1 = $page->addMedia(UploadedFile::fake()->image('image1.jpg'))
        ->withCustomProperties(['block' => $block1Id])
        ->toMediaCollection(ImageBlock::getName());

    $media2 = $page->addMedia(UploadedFile::fake()->image('image2.jpg'))
        ->withCustomProperties(['block' => $block2Id])
        ->toMediaCollection(ImageBlock::getName());

    // Get all media for this collection
    $allMedia = $page->getMedia(ImageBlock::getName());

    expect($allMedia)->toHaveCount(2);

    // Filter media by block1Id
    $block1Media = $allMedia->filter(function (Media $item) use ($block1Id) {
        return $item->getCustomProperty('block') === $block1Id;
    });

    expect($block1Media)->toHaveCount(1)
        ->and($block1Media->first()->id)->toBe($media1->id);

    // Filter media by block2Id
    $block2Media = $allMedia->filter(function (Media $item) use ($block2Id) {
        return $item->getCustomProperty('block') === $block2Id;
    });

    expect($block2Media)->toHaveCount(1)
        ->and($block2Media->first()->id)->toBe($media2->id);
});

it('generates media conversions for image block', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg', 1600, 900))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    // ImageBlock should generate conversions (contain and crop)
    // Wait for conversions to be generated
    $media->refresh();

    expect($media->hasGeneratedConversion('crop'))->toBeTrue()
        ->and($media->hasGeneratedConversion('contain'))->toBeTrue()
        ->and($media->hasGeneratedConversion('thumbnail'))->toBeTrue();
});

it('can retrieve media URL from content block', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    // Get media URL
    $url = $media->getUrl();

    expect($url)->toBeString()
        ->and($url)->toContain('test.jpg');

    // Get conversion URL
    $cropUrl = $media->getUrl('crop');
    expect($cropUrl)->toBeString();
});

it('prevents media mixup between different blocks of same type', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    // Create 3 text-image blocks, each with its own image
    $blocks = [
        ['id' => BlockIdField::generateBlockId(), 'filename' => 'image-block-1.jpg'],
        ['id' => BlockIdField::generateBlockId(), 'filename' => 'image-block-2.jpg'],
        ['id' => BlockIdField::generateBlockId(), 'filename' => 'image-block-3.jpg'],
    ];

    foreach ($blocks as $block) {
        $page->addMedia(UploadedFile::fake()->image($block['filename']))
            ->withCustomProperties(['block' => $block['id']])
            ->toMediaCollection(TextImageBlock::getName());
    }

    $allMedia = $page->getMedia(TextImageBlock::getName());
    expect($allMedia)->toHaveCount(3);

    // Verify each block can retrieve only its own media
    foreach ($blocks as $block) {
        $blockMedia = $allMedia->filter(function (Media $item) use ($block) {
            return $item->getCustomProperty('block') === $block['id'];
        });

        expect($blockMedia)->toHaveCount(1)
            ->and($blockMedia->first()->file_name)->toBe($block['filename']);
    }
});

it('stores media in correct collection per block type', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $imageBlockId = BlockIdField::generateBlockId();
    $textImageBlockId = BlockIdField::generateBlockId();

    // Upload to different block types
    $imageMedia = $page->addMedia(UploadedFile::fake()->image('image.jpg'))
        ->withCustomProperties(['block' => $imageBlockId])
        ->toMediaCollection(ImageBlock::getName());

    $textImageMedia = $page->addMedia(UploadedFile::fake()->image('text-image.jpg'))
        ->withCustomProperties(['block' => $textImageBlockId])
        ->toMediaCollection(TextImageBlock::getName());

    // Verify collections are different
    expect($imageMedia->collection_name)->toBe(ImageBlock::getName())
        ->and($textImageMedia->collection_name)->toBe(TextImageBlock::getName())
        ->and($imageMedia->collection_name)->not()->toBe($textImageMedia->collection_name);

    // Verify page has media in both collections
    expect($page->getMedia(ImageBlock::getName()))->toHaveCount(1)
        ->and($page->getMedia(TextImageBlock::getName()))->toHaveCount(1);
});

it('can delete media without affecting other blocks', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $block1Id = BlockIdField::generateBlockId();
    $block2Id = BlockIdField::generateBlockId();

    $media1 = $page->addMedia(UploadedFile::fake()->image('image1.jpg'))
        ->withCustomProperties(['block' => $block1Id])
        ->toMediaCollection(ImageBlock::getName());

    $media2 = $page->addMedia(UploadedFile::fake()->image('image2.jpg'))
        ->withCustomProperties(['block' => $block2Id])
        ->toMediaCollection(ImageBlock::getName());

    expect($page->getMedia(ImageBlock::getName()))->toHaveCount(2);

    // Delete media from block1
    $media1->delete();

    // Verify only block2's media remains
    $remainingMedia = $page->fresh()->getMedia(ImageBlock::getName());

    expect($remainingMedia)->toHaveCount(1)
        ->and($remainingMedia->first()->id)->toBe($media2->id)
        ->and($remainingMedia->first()->getCustomProperty('block'))->toBe($block2Id);
});

it('validates media conversions have correct dimensions', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    // Upload a large image
    $media = $page->addMedia(UploadedFile::fake()->image('large.jpg', 2400, 1600))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    $media->refresh();

    // Get crop conversion path
    $cropPath = $media->getPath('crop');

    // Verify crop conversion exists
    expect(file_exists($cropPath))->toBeTrue();

    // Get image dimensions
    $imageInfo = getimagesize($cropPath);

    // ImageBlock uses 1200x630 dimensions for crop
    expect($imageInfo[0])->toBe(1200) // width
        ->and($imageInfo[1])->toBe(630); // height
});

it('handles media cleanup when page is deleted', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    $mediaId = $media->id;

    expect(Media::find($mediaId))->not()->toBeNull();

    // Delete the page - Spatie MediaLibrary should clean up associated media
    $page->delete();

    // Verify media was deleted
    expect(Media::find($mediaId))->toBeNull();
});
