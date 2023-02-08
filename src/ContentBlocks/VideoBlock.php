<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Textarea;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

class VideoBlock extends AbstractFilamentFlexibleContentBlock
{
    public ?string $embedCode;

    /**
     * Create a new component instance.
     *
     * @param  HasContentBlocks  $record
     * @param  array|null  $blockData
     */
    public function __construct(HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->embedCode = $blockData['embed_code'] ?? null;
    }

    public static function getNameSuffix(): string
    {
        return 'video';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-video-camera';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            Textarea::make('embed_code')
                ->label(static::getFieldLabel('label'))
                ->hint(static::getFieldLabel('help'))
                ->hintIcon('heroicon-s-question-mark-circle')
                ->rows(2)
                ->required(),
            BlockSpatieMediaLibraryFileUpload::make('overlay_image')
                ->collection('test')
                ->label(self::getFieldLabel('overlay_image')),
        ];
    }
}
