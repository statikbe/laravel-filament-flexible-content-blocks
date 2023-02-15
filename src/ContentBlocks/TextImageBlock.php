<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionBuilder;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImagePositionField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class TextImageBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;
    use HasCallToAction;

    const CONVERSION_DEFAULT = 'default';

    public ?string $title;

    public ?string $content;

    public ?string $imageId;

    public ?string $imageTitle;

    public ?string $imageCopyright;

    public ?string $imagePosition;

    public ?CallToActionData $callToAction;

    /**
     * Create a new component instance.
     *
     * @param  HasContentBlocks&HasMedia  $record
     * @param  array|null  $blockData
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->title = $blockData['title'] ?? null;
        $this->content = $blockData['content'] ?? null;
        $this->imageId = $blockData['image'] ?? null;
        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->imagePosition = $blockData['image_position'] ?? null;
        $this->callToAction = $blockData['call_to_action'][0]['data'] ? CallToActionData::create($blockData['call_to_action'][0]['data'], CallToActionField::getButtonStyleClasses(self::class)) : null;
    }

    public static function getNameSuffix(): string
    {
        return 'text-image';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-photograph';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            TextInput::make('title')
                ->label(self::getFieldLabel('title')),
            RichEditor::make('content')
                ->label(self::getFieldLabel('content'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            BlockSpatieMediaLibraryFileUpload::make('image')
                ->collection(static::getName())
                ->label(self::getFieldLabel('image'))
                ->maxFiles(1),
            //https://github.com/filamentphp/filament/issues/1284
            TextInput::make('image_title')
                ->label(self::getFieldLabel('image_title'))
                ->maxLength(255),
            TextInput::make('image_copyright')
                ->label(self::getFieldLabel('image_copyright'))
                ->maxLength(255),
            ImagePositionField::create(self::class),
            CallToActionBuilder::create('call_to_action', self::class)
                ->callToActionTypes(self::getCallToActionTypes())
                ->minItems(0)
                ->maxItems(1),
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
