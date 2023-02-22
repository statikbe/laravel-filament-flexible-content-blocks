<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionRepeater;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\GridColumnsField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class CardsBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;
    use HasCallToAction;
    use HasBackgroundColour;

    const CONVERSION_DEFAULT = 'default';

    public ?string $title;

    public array $cards = [];

    public int $gridColumns = 3;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->title = $blockData['title'] ?? null;
        $this->cards = $this->createCards($blockData['cards']);
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->gridColumns = $blockData['grid_columns'] ?? null;
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-view-grid';
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make('title')
                ->label(self::getFieldLabel('title'))
                ->maxLength(255),
            Grid::make(2)->schema([
                GridColumnsField::create(self::class, true),
                BackgroundColourField::create(self::class),
            ]),
            Repeater::make('cards')
                ->label(self::getFieldLabel('cards'))
                ->schema([
                    TextInput::make('title')
                        ->label(self::getFieldLabel('card_title'))
                        ->maxLength(255),
                    RichEditor::make('text')
                        ->label(self::getFieldLabel('card_text'))
                        ->disableToolbarButtons([
                            'attachFiles',
                        ]),
                    BlockSpatieMediaLibraryFileUpload::make('image')
                        ->collection(static::getName())
                        ->label(self::getFieldLabel('card_image'))
                        ->maxFiles(1),
                    CallToActionRepeater::create('card_call_to_action', self::class)
                        ->callToActionTypes(self::getCallToActionTypes())
                        ->minItems(0)
                        ->maxItems(2),
                ])
                ->itemLabel(function (array $state): ?string {
                    return $state['title'] ?? null;
                })
                ->collapsible()
                ->minItems(1),
        ];
    }

    public static function getNameSuffix(): string
    {
        return 'cards';
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
                    ->fit(Manipulations::FIT_CROP, 800, 420)
                    ->format(Manipulations::FORMAT_WEBP);
                FilamentFlexibleBlocksConfig::mergeConfiguredFlexibleBlockImageConversion(self::class, self::getName(), self::CONVERSION_DEFAULT, $conversion);

                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getCardImageMedia(?string $imageId, ?string $imageTitle, array $attributes = []): ?HtmlableMedia
    {
        if (! $imageId) {
            return null;
        }

        return $this->getHtmlableMedia($imageId, self::CONVERSION_DEFAULT, $imageTitle, $attributes);
    }

    public function getCardImageUrl(string $imageId): ?string
    {
        return $this->getMediaUrl($imageId);
    }

    /**
     * @return CardData[]
     */
    private function createCards(array $cardsBlockData): array
    {
        $cardData = [];
        foreach ($cardsBlockData as $card) {
            $cardData[] = CardData::create(
                $card,
                $this->getCardImageUrl($card['image']),
                $this->getCardImageMedia($card['image'], $card['title']),
                CallToActionField::getButtonStyleClasses(self::class));
        }

        return $cardData;
    }
}
