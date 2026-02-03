<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use MediaEmbed\MediaEmbed;
use MediaEmbed\Object\MediaObject;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\VideoPlatform;

class BackgroundVideo extends Component
{
    public string $videoUrl;

    public VideoPlatform $videoPlatform;

    public string $videoId;

    public function __construct(
        string $videoUrl,
    ) {
        $this->videoUrl = $videoUrl;

        $videoMediaObject = (new MediaEmbed)->parseUrl($videoUrl);
        if ($videoMediaObject) {
            $this->videoPlatform = static::determineVideoPlatform($videoMediaObject);
            $this->videoId = $videoMediaObject->id();
        }
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        return view("filament-flexible-content-blocks::components.{$themePrefix}background-video");
    }

    public static function determineVideoPlatform(MediaObject $videoMediaObject): ?VideoPlatform
    {
        switch (strtolower($videoMediaObject->name())) {
            case 'youtube':
                return VideoPlatform::YOUTUBE;
        }

        return null;
    }
}
