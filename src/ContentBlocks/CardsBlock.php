<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBlockStyle;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasCallToAction;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImageConversionType;
use Statikbe\FilamentFlexibleContentBlocks\Exceptions\LinkableModelNotFoundException;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockStyleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionRepeater;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\GridColumnsField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\ImageConversionTypeField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class CardsBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;
    use HasBlockStyle;
    use HasCallToAction;
    use HasImage;
    use HasImageConversionType;

    public ?string $title;

    public array $cards = [];

    public int $gridColumns = 3;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->title = $blockData['title'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->gridColumns = $blockData['grid_columns'] ?? null;
        $this->setImageConversionType($blockData);
        $this->setBlockStyle($blockData);
        $this->cards = $this->createCards($blockData['cards']);
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-squares-plus';
    }

    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            TextInput::make('title')
                ->label(static::getFieldLabel('title'))
                ->maxLength(255),
            Grid::make(static::hasBlockStyles() ? 3 : 2)->schema([
                GridColumnsField::create(static::class, true),
                BackgroundColourField::create(static::class),
                BlockStyleField::create(static::class),
                ImageConversionTypeField::create(static::class),
            ]),
            Repeater::make('cards')
                ->label(static::getFieldLabel('cards'))
                ->schema([
                    TextInput::make('title')
                        ->label(static::getFieldLabel('card_title'))
                        ->maxLength(255),
                    RichEditor::make('text')
                        ->label(static::getFieldLabel('card_text'))
                        ->disableToolbarButtons([
                            'attachFiles',
                        ]),
                    BlockSpatieMediaLibraryFileUpload::make('image')
                        ->collection(static::getName())
                        ->label(static::getFieldLabel('card_image'))
                        ->maxFiles(1),
                    CallToActionRepeater::create('card_call_to_action', static::class)
                        ->callToActionTypes(static::getCallToActionTypes())
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
        $record->addMediaCollection(static::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                static::addCropImageConversion($record, 800, 420);
                static::addContainImageConversion($record, 800, 420);

                //for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getCardImageMedia(?string $imageId, ?string $imageTitle, ?string $conversion = null, array $attributes = []): ?HtmlableMedia
    {
        if (! $imageId) {
            return null;
        }

        return $this->getHtmlableMedia($imageId, $this->getImageConversionType($conversion), $imageTitle, $attributes);
    }

    public function getCardImageUrl(string $imageId, ?string $conversion = null): ?string
    {
        return $this->getMediaUrl(imageId: $imageId, conversion: $this->getImageConversionType($conversion));
    }

    /**
     * @return CardData[]
     */
    private function createCards(array $cardsBlockData): array
    {
        $cardData = [];
        foreach ($cardsBlockData as $card) {
            try {
                $cardData[] = CardData::create(
                    cardBlockData: $card,
                    imageUrl: $card['image'] ? $this->getCardImageUrl($this->getMediaUuid($card['image'])) : null,
                    imageHtml: $card['image'] ? $this->getCardImageMedia($this->getMediaUuid($card['image']), $card['title']) : null,
                    blockStyle: $this->hasDefaultBlockStyle() ? null : $this->blockStyle,
                    buttonStyleClasses: CallToActionField::getButtonStyleClasses(static::class)
                );
            } catch (LinkableModelNotFoundException $ex) {
                $ex->setRecord($this->record);
                throw $ex;
            }
        }

        return $cardData;
    }

    public function getSearchableContent(): array
    {
        $searchable = [];

        $this->addSearchableContent($searchable, $this->title);
        foreach ($this->cards as $card) {
            /* @var CardData $card */
            $this->addSearchableContent($searchable, $card->title);
            $this->addSearchableContent($searchable, $card->text);
        }

        return $searchable;
    }

    public function getImageUuids(): array
    {
        $uuids = [];
        if ($this->blockData['cards']) {
            foreach ($this->blockData['cards'] as $card) {
                if ($card['image']) {
                    $uuids[] = $card['image'];
                }
            }
        }

        return $uuids;
    }
}
