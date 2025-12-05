<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Builder\ContentBlockWithPreview;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\BlockIdField;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

/**
 * This is the base class from which all flexible content blocks should inherit.
 */
abstract class AbstractContentBlock extends Component
{
    public final const CONVERSION_CROP = 'crop';

    public final const CONVERSION_CONTAIN = 'contain';

    protected static Block $block;

    public HasContentBlocks&HasMedia $record;

    public ?array $blockData;

    private string $blockId;

    /**
     * Create a new component instance.
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        $this->record = $record;
        $this->blockData = $blockData;

        // block id:
        if (! isset($this->blockData[BlockIdField::FIELD]) || ! $this->blockData[BlockIdField::FIELD]) {
            // initialise the ID for a new block, then never change it.
            $this->blockData[BlockIdField::FIELD] = BlockIdField::generateBlockId();
        }

        $this->blockId = $this->blockData[BlockIdField::FIELD];
    }

    /**
     * Get the name/type of this block
     */
    abstract public static function getName(): string;

    /**
     * Get the heroicon of this block
     */
    abstract public static function getIcon(): string;

    /**
     * Get translated label of this block
     */
    abstract public static function getLabel(): string;

    /**
     * Get the translated label of this block in the context of the given state.
     * You can use this to display the title or other state data in the block label.
     */
    public static function getContextualLabel(?array $state): ?string
    {
        return null;
    }

    /**
     * Get the translated label of the given field.
     */
    abstract public static function getFieldLabel(string $field): string;

    /**
     * Make a new Filament Block instance for this flexible block.
     */
    public static function make(): Block
    {
        return ContentBlockWithPreview::make(static::getName())
            ->label(function (?array $state) {
                return static::getContextualLabel($state) ?? static::getLabel();
            })
            ->icon(static::getIcon())
            ->schema(static::getFilamentBlockSchema())
            ->visible(static::visible())
            ->contentBlockClass(static::class);
    }

    /**
     * Returns the final block schema with a block ID hidden field.
     */
    protected static function getFilamentBlockSchema(): Closure
    {
        return function (\Filament\Schemas\Components\Component $component) {
            return array_merge([
                // keep track of block id:
                BlockIdField::create(),
            ],
                $component->evaluate(static::makeFilamentSchema()));
        };
    }

    /**
     * Returns the block's form schema consisting of an list of Filament form components or a closure that returns such a list.
     *
     * @return array<\Filament\Schemas\Components\Component>|Closure
     */
    abstract protected static function makeFilamentSchema(): array|Closure;

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    abstract public function render();

    public static function getPreviewView(): string
    {
        $themePrefix = FilamentFlexibleBlocksConfig::getViewThemePrefix();

        return "filament-flexible-content-blocks::content-blocks.{$themePrefix}preview";
    }

    /**
     * Registers media collection and conversions for the media fields of this block to the model.
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        // overwrite to add collection and conversion here if the block has images
    }

    /**
     * Sets the visibility of the block in the builder. Call this to hide or show the block in the builder selector.
     *
     * @return bool|Closure $condition
     */
    public static function visible(): bool|Closure
    {
        return true;
    }

    /**
     * Sets the visibility of block styles on the block form. Call this to hide or show the block styles field in the block form.
     *
     * @return bool|Closure $condition
     */
    public static function hasBlockStyles(): bool|Closure
    {
        return FilamentFlexibleBlocksConfig::isBlockStyleEnabled(static::class);
    }

    /**
     * Return an array of strings with searchable content of the block fields.
     *
     * @return array<string>
     */
    abstract public function getSearchableContent(): array;

    /**
     * @param  array<string>  $searchableContent
     * @return array<string>
     */
    protected function addSearchableContent(array &$searchableContent, ?string $textContent): array
    {
        if ($textContent && ! empty(trim($textContent))) {
            $searchableContent[] = $textContent;
        }

        return $searchableContent;
    }

    public function replaceParameters(?string $content): ?string
    {
        return FilamentFlexibleContentBlocks::replaceParameters($content);
    }

    public function getBlockId(): string
    {
        return $this->blockId;
    }
}
