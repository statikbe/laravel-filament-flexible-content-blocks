<?php

namespace Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;

    use Filament\Forms\Components\Builder\Block;
    use Filament\Forms\Components\RichEditor;

    class Text extends AbstractContentBlock
    {
        public ?string $content;

        /**
         * Create a new component instance.
         *
         * @param  array|null  $blockData
         */
        public function __construct(?array $blockData)
        {
            $this->content = $blockData['content'] ?? null;
        }

        public static function getName(): string {
            return 'text';
        }

        public static function getFilamentBlock(): Block
        {
            return Block::make(self::getName())
                ->label(trans('merchants.article.body_paragraph'))
                ->schema([
                    RichEditor::make('content')
                        ->label(trans('merchants.article.body_paragraph_content'))
                        ->disableToolbarButtons([
                            'attachFiles',
                        ])
                        ->required(),
                ])->icon('heroicon-o-view-list');
        }

        /**
         * Get the view / contents that represent the component.
         *
         * @return \Illuminate\Contracts\View\View|\Closure|string
         */
        public function render()
        {
            return view('components.blocks.paragraph');
        }
    }
