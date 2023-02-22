<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class VideoBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;

    const CONVERSION_DEFAULT = 'default';

    public ?string $embedCode;

    public ?string $overlayImageId;

    /**
     * Create a new component instance.
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->embedCode = $blockData['embed_code'] ?? null;
        $this->overlayImageId = $blockData['overlay_image'] ?? null;
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
            Grid::make(2)->schema([
                Textarea::make('embed_code')
                    ->label(static::getFieldLabel('embed_code'))
                    ->hint(static::getFieldLabel('help'))
                    ->hintIcon('heroicon-s-question-mark-circle')
                    ->rows(7)
                    ->required(),
                BlockSpatieMediaLibraryFileUpload::make('overlay_image')
                    ->collection(static::getName())
                    ->label(self::getFieldLabel('overlay_image'))
                    ->maxFiles(1),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        $record->addMediaCollection(self::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                $conversion = $record->addMediaConversion(static::CONVERSION_DEFAULT)
                    ->withResponsiveImages()
                    ->fit(Manipulations::FIT_CROP, 1200, 675)
                    ->format(Manipulations::FORMAT_WEBP);
                FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(self::class, self::getName(), self::CONVERSION_DEFAULT, $conversion);

                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getOverlayImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->overlayImageId, self::CONVERSION_DEFAULT, null, $attributes);
    }

    public function getOverlayImageUrl(): ?string
    {
        return $this->getMediaUrl($this->overlayImageId);
    }
}
