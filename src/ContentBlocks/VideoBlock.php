<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use MediaEmbed\MediaEmbed;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\HtmlableMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BlockSpatieMediaLibraryFileUpload;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\MediaEmbedField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class VideoBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasImage;

    public ?string $embedUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(Model&HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->embedUrl = $blockData['embed_url'] ?? null;
    }

    public static function getNameSuffix(): string
    {
        return 'video';
    }

    public static function getContentSummary(array $state): ?string
    {
        return $state['embed_url'];
    }

    public static function getIcon(): Heroicon|string
    {
        return Heroicon::VideoCamera;
    }

    /**
     * {@inheritDoc}
     */
    protected static function makeFilamentSchema(): array|Closure
    {
        return [
            Grid::make(2)->schema([
                MediaEmbedField::make('embed_url')
                    ->media()
                    ->label(static::getFieldLabel('embed_url'))
                    ->hint(static::getFieldLabel('help'))
                    ->hintIcon(Heroicon::QuestionMarkCircle)
                    ->required(),
                BlockSpatieMediaLibraryFileUpload::make('overlay_image')
                    ->collection(static::getName())
                    ->label(static::getFieldLabel('overlay_image'))
                    ->maxFiles(1),
            ]),
        ];
    }

    public function getEmbedCode(array $attributes = []): ?string
    {
        $mediaObject = (new MediaEmbed)->parseUrl($this->embedUrl);
        if (! $mediaObject) {
            return '';
        }
        $mediaObject->setAttribute($attributes);

        return $mediaObject->getEmbedCode();
    }

    public function getEmbedSrc(array $attributes = []): ?string
    {
        $mediaObject = (new MediaEmbed)->parseUrl($this->embedUrl);
        if (! $mediaObject) {
            return '';
        }

        $mediaObject->setAttribute($attributes);

        return $mediaObject->getEmbedSrc();
    }

    /**
     * {@inheritDoc}
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        $record->addMediaCollection(static::getName())
            ->registerMediaConversions(function (Media $media) use ($record) {
                static::addCropImageConversion($record, 1200, 675);

                // for filament upload field
                $record->addFilamentThumbnailMediaConversion();
            });
    }

    public function getOverlayImageMedia(array $attributes = []): ?HtmlableMedia
    {
        return $this->getHtmlableMedia($this->getBlockId(), static::CONVERSION_CROP, null, $attributes);
    }

    public function getOverlayImageUrl(): ?string
    {
        return $this->getMediaUrl($this->getBlockId());
    }

    public function hasOverlayImage(): bool
    {
        return $this->hasImage($this->getBlockId());
    }
}
