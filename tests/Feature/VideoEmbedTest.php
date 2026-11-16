<?php

use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\VideoPlatform;
use Statikbe\FilamentFlexibleContentBlocks\Rules\MediaEmbedRule;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\BackgroundVideo;

beforeEach(function () {
    setupDefaultContentBlocks();
});

function videoBlock(?string $embedUrl): VideoBlock
{
    return new VideoBlock(Page::factory()->create(['content_blocks' => []]), [
        'block_id' => BlockIdField::generateBlockId(),
        'embed_url' => $embedUrl,
    ]);
}

it('renders the embed code with the given iframe attributes', function () {
    $code = videoBlock('https://www.youtube.com/watch?v=dQw4w9WgXcQ')->getEmbedCode([
        'class' => 'w-full aspect-video',
        'allowfullscreen' => true,
    ]);

    expect($code)->toContain('<iframe')
        ->toContain('youtube')
        ->toContain('dQw4w9WgXcQ')
        ->toContain('class="w-full aspect-video"');
});

it('returns the embed src', function () {
    expect(videoBlock('https://www.youtube.com/watch?v=dQw4w9WgXcQ')->getEmbedSrc())
        ->toContain('youtube')
        ->toContain('/embed/dQw4w9WgXcQ');
});

it('returns an empty embed for an unsupported url', function () {
    $block = videoBlock('https://example.com/not-a-video');

    expect($block->getEmbedCode())->toBe('')
        ->and($block->getEmbedSrc())->toBe('');
});

it('detects the youtube platform and video id for a background video', function () {
    $video = new BackgroundVideo('https://youtu.be/dQw4w9WgXcQ');

    expect($video->videoPlatform)->toBe(VideoPlatform::YOUTUBE)
        ->and($video->videoId)->toBe('dQw4w9WgXcQ');
});

it('validates media embed urls', function () {
    $fails = function (string $url): bool {
        $failed = false;
        (new MediaEmbedRule)->validate('embed_url', $url, function () use (&$failed) {
            $failed = true;
        });

        return $failed;
    };

    expect($fails('https://vimeo.com/76979871'))->toBeFalse()
        ->and($fails('https://example.com/not-a-video'))->toBeTrue();
});
