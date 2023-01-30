<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\Textarea;

    class HtmlBlock extends AbstractContentBlock {
        public ?string $content;

        public static function getName(): string {
            return 'html';
        }

        /**
         * Create a new component instance.
         *
         * @param  array|null  $blockData
         */
        public function __construct(?array $blockData)
        {
            $this->content = $blockData['content'] ?? null;
        }

        public static function getFilamentBlock(): Block {
            return parent::getFilamentBlock()
                ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_html.title'))
                ->schema([
                    Textarea::make('content')
                        ->label(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_html.content_lbl'))
                        ->hint(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.content_block_html.html_help'))
                        ->hintIcon('heroicon-s-question-mark-circle')
                        ->rows(5)
                        ->required(),
                ])->icon('heroicon-o-view-list');
        }

        public function render() {
            return view('components.content-blocks.html');
        }
    }
