<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Builder;

use Filament\Forms\Components\Builder\Block;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Vite;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\View\ThemeScopedView;

class ContentBlockWithPreview extends Block
{
    /**
     * @var class-string
     */
    public string $contentBlockClass;

    public function setup(): void
    {
        parent::setup();

        $this->preview(AbstractContentBlock::getPreviewView());
    }

    public function renderPreview(array $data): View
    {
        $component = new $this->contentBlockClass($this->getRecord(), $data);
        $stylesheetPath = FilamentFlexibleBlocksConfig::getBlockPreviewStylesheet();

        return ThemeScopedView::make($component->getPreviewViewForModel($this->getRecord()), $this->getRecord())->with([
            'component' => $component,
            'stylesheet' => $stylesheetPath ? Vite::asset($stylesheetPath) : null,
        ]);
    }

    public function contentBlockClass(string $contentBlockClass): static
    {
        $this->contentBlockClass = $contentBlockClass;

        return $this;
    }
}
