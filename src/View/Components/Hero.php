<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasHeroImageAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasIntroAttribute;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class Hero extends Component
{
    public HasPageAttributes|HasHeroImageAttributes|HasIntroAttribute $page;

    public string $title;

    public ?string $intro = null;

    public ?string $heroImageTitle = null;

    public ?string $heroImageCopyright = null;

    public function __construct(HasPageAttributes|HasHeroImageAttributes|HasIntroAttribute $page)
    {
        $this->page = $page;
        $this->title = $page->title;

        if (isset($page->intro)) {
            $this->intro = $page->intro;
        }

        if (isset($page->hero_image_title)) {
            $this->heroImageTitle = $page->hero_image_title;
        }

        if (isset($page->hero_image_copyright)) {
            $this->heroImageCopyright = $page->hero_image_copyright;
        }
    }

    public function getHeroImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia
    {
        if (method_exists($this->page, 'getHeroImageMedia')) {
            return $this->page->getHeroImageMedia($conversion, $attributes);
        }

        return null;
    }

    public function hasHeroImage(): bool
    {
        return $this->page->hasHeroImage();
    }

    public function render()
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        return view("filament-flexible-content-blocks::components.{$themePrefix}hero");
    }
}
