<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Textarea;
    use Illuminate\View\Component;

    class VideoBlock extends AbstractContentBlock
    {
        public ?string $embedCode;

        /**
         * Create a new component instance.
         *
         * @param  array|null  $blockData
         */
        public function __construct(?array $blockData)
        {
            $this->embedCode = $blockData['embed_code'] ?? null;
        }

        public static function getFilamentBlock(): Block
        {
            return parent::getFilamentBlock()
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_video.title'))
                ->schema([
                    Textarea::make('embed_code')
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_video.embed_code_lbl'))
                        ->hint(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_video.video_help'))
                        ->hintIcon('heroicon-s-question-mark-circle')
                        ->rows(2)
                        ->required(),
                ])->icon('heroicon-o-video-camera');
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View|\Closure|string
         */
        public function render()
        {
            return view('components.content-blocks.video');
        }

        public static function getName(): string
        {
            return 'video';
        }
    }
