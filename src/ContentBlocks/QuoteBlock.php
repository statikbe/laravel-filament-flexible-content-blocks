<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImageConversionType;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImagePositionField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class QuoteBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;
    use HasImage;
    use HasImageConversionType;

    public ?string $quote;

    public ?string $author;

    public ?string $imageTitle;

    public ?string $imageCopyright;

    public ?string $imagePosition;

    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->quote = $blockData['quote'] ?? null;
        $this->author = $blockData['author'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;

        $this->imageTitle = $blockData['image_title'] ?? null;
        $this->imageCopyright = $blockData['image_copyright'] ?? null;
        $this->imagePosition = $blockData['image_position'] ?? null;
        $this->setImageConversionType($blockData);

        $this->setBlockStyle($blockData);
    }

    public static function getNameSuffix(): string
    {
        return 'quote';
    }

    public static function getContentSummary(array $state): ?string
    {
        return static::convertRichTextToText($state['quote']);
    }

    public static function getIcon(): Heroicon|string
    {
        return Heroicon::ChatBubbleBottomCenterText;
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            RichEditor::make('quote')
                ->label(static::getFieldLabel('quote'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            TextInput::make('author')
                ->label(static::getFieldLabel('author'))
                ->maxLength(255),
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
                    ImagePositionField::create(static::class)
                        ->required(function (Get $get) {
                            return (bool) $get('image');
                        }),
                ])->columnSpan(1),
            ]),
            Grid::make(2)->schema([
                BackgroundColourField::create(static::class),
                BlockStyleField::create(static::class),
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

                // for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->getBlockId(), $this->getImageConversionType($conversion), $this->imageTitle, $attributes);
    }

    public function getImageUrl(?string $conversion = null): ?string
    {
        return $this->getMediaUrl(blockId: $this->getBlockId(), conversion: $this->getImageConversionType($conversion));
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->quote);
        $this->addSearchableContent($searchable, $this->author);

        return $searchable;
    }
}
