<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\VideoPlatform;

class VideoBackground extends Component
{
    public string $videoUrl;
    public VideoPlatform $videoPlatform;
    public string $videoId;

    public function __construct(
        string $videoUrl,
    ) {
        $this->videoUrl = $videoUrl;
        $this->videoPlatform = static::determineVideoPlatform($videoUrl);
        $this->videoId = static::determineVideoId($videoUrl, $this->videoPlatform);
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        return view("filament-flexible-content-blocks::components.{$themePrefix}video-background");
    }

    public static function determineVideoPlatform(string $videoUrl): ?VideoPlatform {
        if (Str::contains($videoUrl, 'youtube', ignoreCase: true)) {
            return VideoPlatform::YOUTUBE;
        }

        return null;
    }

    public static function determineVideoId(string $videoUrl, VideoPlatform $videoPlatform): ?string {
        switch ($videoPlatform) {
            case VideoPlatform::YOUTUBE:
                /**
                 * Supported formats:
                 * - https://www.youtube.com/watch?v=ID
                 * - https://youtu.be/ID
                 * - https://www.youtube.com/embed/ID
                 */
                preg_match(
                    '/(?:v=|youtu\.be\/|embed\/)([A-Za-z0-9_-]{11})/',
                    $videoUrl,
                    $matches,
                );

                return $matches[1] ?? null;
        }

        return null;
    }
}
