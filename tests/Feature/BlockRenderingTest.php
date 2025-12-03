<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

beforeEach(function () {
    setupDefaultContentBlocks();
    setupViewTheme();
    setupFakeStorage();
});

it('can instantiate TextImageBlock from data', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'block_id' => BlockIdField::generateBlockId(),
        'title' => 'Test Block Title',
        'text' => '<p>Test content</p>',
        'image_position' => 'left',
    ];

    $block = new TextImageBlock($page, $blockData);

    expect($block)->toBeInstanceOf(TextImageBlock::class)
        ->and($block->title)->toBe('Test Block Title')
        ->and($block->text)->toBe('<p>Test content</p>')
        ->and($block->imagePosition)->toBe('left');
});

it('can instantiate ImageBlock from data', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'block_id' => BlockIdField::generateBlockId(),
        'image_title' => 'Beautiful Landscape',
        'image_copyright' => 'Photo by John Doe',
        'image_position' => 'center',
    ];

    $block = new ImageBlock($page, $blockData);

    expect($block)->toBeInstanceOf(ImageBlock::class)
        ->and($block->imageTitle)->toBe('Beautiful Landscape')
        ->and($block->imageCopyright)->toBe('Photo by John Doe')
        ->and($block->imagePosition)->toBe('center');
});

it('can instantiate VideoBlock from data', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'block_id' => BlockIdField::generateBlockId(),
        'embed_url' => 'https://youtube.com/watch?v=test123',
    ];

    $block = new VideoBlock($page, $blockData);

    expect($block)->toBeInstanceOf(VideoBlock::class)
        ->and($block->embedUrl)->toBe('https://youtube.com/watch?v=test123');
});

it('can instantiate QuoteBlock from data', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'block_id' => BlockIdField::generateBlockId(),
        'quote' => 'To be or not to be',
        'author' => 'William Shakespeare',
    ];

    $block = new QuoteBlock($page, $blockData);

    expect($block)->toBeInstanceOf(QuoteBlock::class)
        ->and($block->quote)->toBe('To be or not to be')
        ->and($block->author)->toBe('William Shakespeare');
});

it('can instantiate HtmlBlock from data', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'block_id' => BlockIdField::generateBlockId(),
        'content' => '<div class="custom-content">Custom HTML</div>',
    ];

    $block = new HtmlBlock($page, $blockData);

    expect($block)->toBeInstanceOf(HtmlBlock::class)
        ->and($block->content)->toBe('<div class="custom-content">Custom HTML</div>');
});

it('generates block ID if not provided', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockData = [
        'title' => 'Test Block',
    ];

    $block = new TextImageBlock($page, $blockData);

    expect($block->getBlockId())->toBeString()
        ->and(strlen($block->getBlockId()))->toBeGreaterThan(0);
});

it('preserves block ID if provided', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $blockId = BlockIdField::generateBlockId();
    $blockData = [
        'block_id' => $blockId,
        'title' => 'Test Block',
    ];

    $block = new TextImageBlock($page, $blockData);

    expect($block->getBlockId())->toBe($blockId);
});

it('returns correct view name for TextImageBlock', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $block = new TextImageBlock($page, ['block_id' => BlockIdField::generateBlockId()]);

    $view = $block->render();

    expect($view->name())->toBe('filament-flexible-content-blocks::content-blocks.tailwind.text-image');
});

it('returns correct view name for ImageBlock', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $block = new ImageBlock($page, ['block_id' => BlockIdField::generateBlockId()]);

    $view = $block->render();

    expect($view->name())->toBe('filament-flexible-content-blocks::content-blocks.tailwind.image');
});

it('returns correct view name for VideoBlock', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $block = new VideoBlock($page, ['block_id' => BlockIdField::generateBlockId()]);

    $view = $block->render();

    expect($view->name())->toBe('filament-flexible-content-blocks::content-blocks.tailwind.video');
});

it('returns correct view name for QuoteBlock', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $block = new QuoteBlock($page, ['block_id' => BlockIdField::generateBlockId()]);

    $view = $block->render();

    expect($view->name())->toBe('filament-flexible-content-blocks::content-blocks.tailwind.quote');
});

it('TextImageBlock has correct data properties', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $block = new TextImageBlock($page, [
        'block_id' => BlockIdField::generateBlockId(),
        'title' => 'My Test Title',
        'text' => '<p>Test paragraph</p>',
        'image_position' => 'left',
    ]);

    // Verify all properties are accessible
    expect($block->title)->toBe('My Test Title')
        ->and($block->text)->toBe('<p>Test paragraph</p>')
        ->and($block->imagePosition)->toBe('left');
});

it('QuoteBlock has correct data properties', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $block = new QuoteBlock($page, [
        'block_id' => BlockIdField::generateBlockId(),
        'quote' => 'Life is beautiful',
        'author' => 'Anonymous',
    ]);

    // Verify all properties are accessible
    expect($block->quote)->toBe('Life is beautiful')
        ->and($block->author)->toBe('Anonymous');
});

it('HtmlBlock has correct data properties', function () {
    $page = Page::factory()->create(['content_blocks' => []]);

    $block = new HtmlBlock($page, [
        'block_id' => BlockIdField::generateBlockId(),
        'content' => '<div class="custom">Custom Content</div>',
    ]);

    // Verify content property is accessible
    expect($block->content)->toBe('<div class="custom">Custom Content</div>');
});

it('renders ImageBlock with media', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    // Add media to the page
    $media = $page->addMedia(UploadedFile::fake()->image('test.jpg', 1200, 630))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    $block = new ImageBlock($page, [
        'block_id' => $blockId,
        'image_title' => 'Test Image',
        'image_position' => 'center',
    ]);

    $imageMedia = $block->getImageMedia();

    expect($imageMedia)->not()->toBeNull();
});

it('can check if ImageBlock has media after adding it', function () {
    $page = Page::factory()->create(['content_blocks' => []]);
    $blockId = BlockIdField::generateBlockId();

    // Add media first
    $page->addMedia(UploadedFile::fake()->image('test.jpg'))
        ->withCustomProperties(['block' => $blockId])
        ->toMediaCollection(ImageBlock::getName());

    // Create block after media is attached
    $block = new ImageBlock($page, [
        'block_id' => $blockId,
        'image_title' => 'Test Image',
    ]);

    // Now should have media
    expect($block->hasImage($blockId))->toBeTrue();
});

it('stores block data in correct format for persistence', function () {
    $page = Page::factory()->create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'content_blocks' => [
            [
                'type' => 'text-image',
                'data' => [
                    'title' => 'Block 1',
                    'text' => '<p>Content 1</p>',
                ],
            ],
            [
                'type' => 'quote',
                'data' => [
                    'quote' => 'Famous quote',
                    'author' => 'Famous person',
                ],
            ],
        ],
    ]);

    $contentBlocks = $page->content_blocks;

    expect($contentBlocks)->toBeArray()
        ->and($contentBlocks)->toHaveCount(2)
        ->and($contentBlocks[0]['type'])->toBe('text-image')
        ->and($contentBlocks[0]['data']['title'])->toBe('Block 1')
        ->and($contentBlocks[1]['type'])->toBe('quote')
        ->and($contentBlocks[1]['data']['quote'])->toBe('Famous quote');
});

it('different block types have unique type identifiers', function () {
    $textImageType = TextImageBlock::getName();
    $imageType = ImageBlock::getName();
    $videoType = VideoBlock::getName();
    $quoteType = QuoteBlock::getName();

    // All type names should be unique
    $types = [$textImageType, $imageType, $videoType, $quoteType];

    expect(count($types))->toBe(count(array_unique($types)))
        ->and($textImageType)->not()->toBe($imageType)
        ->and($imageType)->not()->toBe($videoType)
        ->and($videoType)->not()->toBe($quoteType);
});

it('blocks get name suffix for view resolution', function () {
    expect(TextImageBlock::getNameSuffix())->toBe('text-image')
        ->and(ImageBlock::getNameSuffix())->toBe('image')
        ->and(VideoBlock::getNameSuffix())->toBe('video')
        ->and(QuoteBlock::getNameSuffix())->toBe('quote')
        ->and(HtmlBlock::getNameSuffix())->toBe('html');
});

it('blocks get correct type name for data storage', function () {
    expect(TextImageBlock::getName())->toBe('filament-flexible-content-blocks::text-image')
        ->and(ImageBlock::getName())->toBe('filament-flexible-content-blocks::image')
        ->and(VideoBlock::getName())->toBe('filament-flexible-content-blocks::video')
        ->and(QuoteBlock::getName())->toBe('filament-flexible-content-blocks::quote')
        ->and(HtmlBlock::getName())->toBe('filament-flexible-content-blocks::html');
});
