<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Builder\Block;
use Illuminate\View\Component;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

/**
 * This is the base class from which all flexible content blocks should inherit.
 */
abstract class AbstractContentBlock extends Component
{
    public HasContentBlocks&HasMedia $record;

    public ?array $blockData;

    /**
     * Create a new component instance.
     *
     * @param  HasContentBlocks&HasMedia  $record
     * @param  array|null  $blockData
     */
    public function __construct(HasContentBlocks&HasMedia $record, ?array $blockData)
    {
        $this->record = $record;
        $this->blockData = $blockData;
    }

    /**
     * Get the name/type of this block
     *
     * @return string
     */
    abstract public static function getName(): string;

    /**
     * Get the heroicon of this block
     *
     * @return string
     */
    abstract public static function getIcon(): string;

    /**
     * Get translated label of this block
     *
     * @return string
     */
    abstract public static function getLabel(): string;

    /**
     * Get the translated label of the given field.
     *
     * @param  string  $field
     * @return string
     */
    abstract public static function getFieldLabel(string $field): string;

    /**
     * Make a new Filament Block instance for this flexible block.
     *
     * @return Block
     */
    public static function make(): Block
    {
        return Block::make(static::getName())
            ->label(static::getLabel())
            ->icon(static::getIcon())
            ->schema(static::makeFilamentSchema());
    }

    /**
     * Returns the block's form schema consisting of an list of Filament form components or a closure that returns such a list.
     *
     * @return array<\Filament\Forms\Components\Component>|Closure
     */
    abstract protected static function makeFilamentSchema(): array|Closure;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    abstract public function render();

    /**
     * Registers media collection and conversions for the media fields of this block to the model.
     *
     * @param  HasMedia&HasMediaAttributes  $record
     * @return void
     */
    public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
    {
        //overwrite to add collection and conversion here if the block has images
    }
}
