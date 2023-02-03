<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Filament\Facades\Filament;
use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\HasFilamentContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class ContentBlocks extends Component
{
    /**
     * @var array|AbstractContentBlock[]
     */
    public array $contentBlocks;

    /**
     * @param  HasContentBlocks  $record
     */
    public function __construct(HasContentBlocks $record)
    {
        $this->contentBlocks = $this->createBlocks($record);
    }

    /**
     * Transforms the JSON block data into content block components that can be rendered.
     *
     * @return array<AbstractContentBlock>
     */
    private function createBlocks(HasContentBlocks $record): array
    {
        $resource = Filament::getModelResource($record::class);
        /* @var HasFilamentContentBlocks $resource */
        $blockClasses = $resource::getContentBlockClasses();
        $blockClassIndex = collect($blockClasses)->mapWithKeys(fn ($item, $key) => [$item::getName() => $item]);
        $blocks = [];

        foreach ($record->content_blocks as $blockData) {
            if ($blockClassIndex->has($blockData['type'])) {
                $blockClass = $blockClassIndex->get($blockData['type']);
                $blocks[] = new $blockClass($blockData['data']);
            }
        }

        return $blocks;
    }

    public function render()
    {
        return view('filament-flexible-content-blocks::components.content-blocks');
    }
}
