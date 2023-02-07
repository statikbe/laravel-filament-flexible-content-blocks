<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class TextImageBlock extends AbstractFilamentFlexibleContentBlock
{
    const CONVERSION_DEFAULT = 'default';

    public ?string $title;

    public ?string $content;

    public ?string $imageId;

    public ?string $imageTitle;

    public ?string $imageCopyright;

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
    }

    public static function getNameSuffix(): string
    {
        return 'text-image';
    }

    public static function getIcon(): string
    {
        return 'heroicon-o-view-list';
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|\Closure
    {
        return [
            TextInput::make('title')
                ->label(self::getFieldLabel('title'))
                ->required(),
            RichEditor::make('content')
                ->label(self::getFieldLabel('content'))
                ->disableToolbarButtons([
                    'attachFiles',
                ])
                ->required(),
            BlockSpatieMediaLibraryFileUpload::make('image')
                ->collection(static::getName())
                ->label(self::getFieldLabel('image')),
            //https://github.com/filamentphp/filament/issues/1284
            TextInput::make('image_title')
                ->label(self::getFieldLabel('image_title'))
                ->maxLength(255),
            TextInput::make('image_copyright')
                ->label(self::getFieldLabel('image_copyright'))
                ->maxLength(255),
        ];
    }

    public function getImageUrl(): ?string
    {
        if (! $this->imageId) {
            return null;
        }

        /* @var HasMedia $recordWithMedia */
        $recordWithMedia = $this->record;
        $media = $recordWithMedia->getMedia(static::getName(), function (Media $media) {
            return $media->uuid === $this->imageId;
        });

        if (! $media->isEmpty()) {
            return $media[0]->getFullUrl();
        } else {
            return null;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('filament-flexible-content-blocks::content-blocks.text-image');
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
        return $this->getHtmlableMedia(self::CONVERSION_DEFAULT, $this->imageTitle, $attributes);
    }
}
