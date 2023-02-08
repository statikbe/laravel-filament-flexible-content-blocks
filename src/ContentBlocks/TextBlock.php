<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\RichEditor;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class TextBlock extends AbstractFilamentFlexibleContentBlock
{
    public ?string $content;

    /**
     * Create a new component instance.
     *
     * @param  HasContentBlocks  $record
     * @param  array|null  $blockData
     */
    public function __construct(HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->content = $blockData['content'] ?? null;
    }

    public static function getNameSuffix(): string
    {
        return 'text';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-view-list';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            RichEditor::make('content')
                ->label(self::getFieldLabel('label'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
        ];
    }
}
