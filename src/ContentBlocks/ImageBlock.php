<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\TextInput;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ImagePositionField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class ImageBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;

    const CONVERSION_DEFAULT = 'default';

    public ?string $imageId;

    public ?string $imageTitle;

    public ?string $imageCopyright;

    public ?string $imagePosition;

    /**
     * Create a new component instance.
     *
     * @param  HasContentBlocks&HasMedia  $record
     * @param  array|null  $blockData
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->imageId = $blockData['image'] ?? null;
        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->imagePosition = $blockData['image_position'] ?? null;
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
            BlockSpatieMediaLibraryFileUpload::make('image')
                ->collection(static::getName())
                ->label(self::getFieldLabel('image'))
                ->maxFiles(1)
                ->required(),
            //https://github.com/filamentphp/filament/issues/1284
            TextInput::make('image_title')
                ->label(self::getFieldLabel('image_title'))
                ->maxLength(255),
            TextInput::make('image_copyright')
                ->label(self::getFieldLabel('image_copyright'))
                ->maxLength(255),
            ImagePositionField::create(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        $record->addMediaCollection(self::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                $record->addMediaConversion(static::CONVERSION_DEFAULT)
                    ->withResponsiveImages()
                    ->fit(Manipulations::FIT_CROP, 1200, 630)
                    ->format(Manipulations::FORMAT_WEBP);
                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->imageId, self::CONVERSION_DEFAULT, $this->imageTitle, $attributes);
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->getMediaUrl($this->imageId);
    }
}
