<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class ContentBlocks extends Component
{
    /**
     * @var array|AbstractContentBlock[]
     */
    public array $contentBlocks = [];

    public function __construct(HasContentBlocks $page)
    {
        $this->contentBlocks = $this->createBlocks($page);
    }

    /**
     * Transforms the JSON block data into content block components that can be rendered.
     *
     * @return array<AbstractContentBlock>
     */
    private function createBlocks(HasContentBlocks $page): array
    {
        $blockClasses = $page::registerContentBlocks();
        $blockClassIndex = collect($blockClasses)->mapWithKeys(fn ($item, $key) => [$item::getName() => $item]);
        $blocks = [];

        foreach ($page->content_blocks as $blockData) {
            if ($blockClassIndex->has($blockData['type'])) {
                $blockClass = $blockClassIndex->get($blockData['type']);
                $blocks[] = new $blockClass($page, $blockData['data']);
            }
        }

        return $blocks;
    }

    public function render()
    {
        return view('filament-flexible-content-blocks::components.content-blocks');
    }
}
