<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components;

    use Illuminate\View\Component;
    use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks\AbstractContentBlock;

    class ContentBlocksBuilder extends Component
    {
        public array $contentBlocks;

        /**
         * @param  array<AbstractContentBlock>  $contentBlocks
         */
        public function __construct(array $contentBlocks)
        {
            $this->contentBlocks = $contentBlocks;
        }

        public function render()
        {
            return 'components.content-blocks-builder';
        }
    }
