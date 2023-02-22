<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImageWidth;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImagePositionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageWidthField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class ImageBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;
    use HasImageWidth;
    use HasBackgroundColour;

    const CONVERSION_DEFAULT = 'default';

    public ?string $imageId;

    public ?string $imageTitle;

    public ?string $imageCopyright;

    public ?string $imagePosition;

    /**
     * Create a new component instance.
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->imageId = $blockData['image'] ?? null;
        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->imagePosition = $blockData['image_position'] ?? null;
        $this->imageWidth = $blockData['image_width'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
    }

    public static function getNameSuffix(): string
    {
        return 'image';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-camera';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            Grid::make(2)->schema([
                BlockSpatieMediaLibraryFileUpload::make('image')
                    ->collection(static::getName())
                    ->label(self::getFieldLabel('image'))
                    ->maxFiles(1),
                Grid::make(1)->schema([
                    TextInput::make('image_title')
                        ->label(self::getFieldLabel('image_title'))
                        ->maxLength(255),
                    TextInput::make('image_copyright')
                        ->label(self::getFieldLabel('image_copyright'))
                        ->maxLength(255),
                    ImagePositionField::create(self::class),
                    ImageWidthField::create(self::class),
                    BackgroundColourField::create(self::class),
                ])->columnSpan(1),
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
                    ->fit(Manipulations::FIT_CROP, 1200, 630)
                    ->format(Manipulations::FORMAT_WEBP);
                FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(self::class, self::getName(), self::CONVERSION_DEFAULT, $conversion);

                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->imageId, self::CONVERSION_DEFAULT, $this->imageTitle, $attributes);
    }

    public function getImageUrl(): ?string
    {
        return $this->getMediaUrl($this->imageId);
    }
}
