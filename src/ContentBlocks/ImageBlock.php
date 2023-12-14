<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImageConversionType;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImageWidth;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageConversionTypeField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImagePositionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageWidthField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class ImageBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;
    use HasImage;
    use HasImageConversionType;
    use HasImageWidth;

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

        $this->imageId = $this->getMediaUuid($blockData['image']) ?? null;
        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->imagePosition = $blockData['image_position'] ?? null;
        $this->imageWidth = $blockData['image_width'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->setImageConversionType($blockData);
        $this->setBlockStyle($blockData);
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
                    ->label(static::getFieldLabel('image'))
                    ->maxFiles(1),
                Grid::make(1)->schema([
                    TextInput::make('image_title')
                        ->label(static::getFieldLabel('image_title'))
                        ->maxLength(255),
                    TextInput::make('image_copyright')
                        ->label(static::getFieldLabel('image_copyright'))
                        ->maxLength(255),
                    ImageConversionTypeField::create(static::class),
                    ImagePositionField::create(static::class),
                    ImageWidthField::create(static::class),
                    BackgroundColourField::create(static::class),
                    BlockStyleField::create(static::class),
                ])->columnSpan(1),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        $record->addMediaCollection(static::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                static::addCropImageConversion($record, 1200, 630);
                static::addContainImageConversion($record, 1200, 630);

                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public static function getImageConversionTypeDefault(): string
    {
        //change the default image conversion for the image block to show the real aspect ratio.
        return static::CONVERSION_CONTAIN;
    }

    public function getImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->imageId, $this->getImageConversionType($conversion), $this->imageTitle, $attributes);
    }

    public function getImageUrl(?string $conversion = null): ?string
    {
        return $this->getMediaUrl(imageId: $this->imageId, conversion: $this->getImageConversionType($conversion));
    }

    public function hasImage(): bool
    {
        return isset($this->imageId) && ! is_null($this->imageId);
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->imageCopyright);

        return $searchable;
    }

    public function getImageUuids(): array
    {
        return $this->imageId ? [$this->imageId] : [];
    }
}
