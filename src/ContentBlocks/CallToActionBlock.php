<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionRepeater;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;
use Statikbe\FilamentFlexibleContentBlocks\Models\Enums\ImageFormat;

class CallToActionBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;
    use HasCallToAction;
    use HasImage;

    const CONVERSION_DEFAULT = 'default';

    public ?string $title;

    public ?string $text;

    public ?string $imageTitle;

    public ?string $imageCopyright;

    /* @var CallToActionData[] $callToActions */
    public ?array $callToActions;

    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->title = $blockData['title'] ?? null;
        $this->text = $blockData['text'] ?? null;
        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->callToActions = $this->createMultipleCallToActions($blockData);
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->setBlockStyle($blockData);
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-cursor-arrow-rays';
    }

    public static function getNameSuffix(): string
    {
        return 'call-to-action';
    }

    public static function getContentSummary(array $state): ?string
    {
        return $state['title'] ?? $state['text'];
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make('title')
                ->label(static::getFieldLabel('title')),
            RichEditor::make('text')
                ->label(static::getFieldLabel('text'))
                ->disableToolbarButtons([
                    'attachFiles',
                ]),
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
                    BackgroundColourField::create(static::class),
                    BlockStyleField::create(static::class),
                ])->columnSpan(1),
            ]),
            CallToActionRepeater::create('call_to_action', static::class)
                ->callToActionTypes(static::getCallToActionTypes())
                ->minItems(FilamentFlexibleBlocksConfig::getCallToActionNumberOfItems(static::class, 'min', 1))
                ->maxItems(FilamentFlexibleBlocksConfig::getCallToActionNumberOfItems(static::class, 'max', 3)),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        $record->addMediaCollection(static::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                $conversion = $record->addMediaConversion(static::CONVERSION_DEFAULT)
                    ->withResponsiveImages()
                    ->fit(Fit::Crop, 1200, 630)
                    ->format(ImageFormat::WEBP->value);
                FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(static::class, static::getName(), static::CONVERSION_DEFAULT, $conversion);

                // for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->getBlockId(), static::CONVERSION_DEFAULT, $this->imageTitle, $attributes);
    }

    public function getImageUrl(): ?string
    {
        return $this->getMediaUrl(blockId: $this->getBlockId());
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->title);
        $this->addSearchableContent($searchable, $this->text);
        $this->addSearchableContent($searchable, $this->imageCopyright);

        return $searchable;
    }
}
