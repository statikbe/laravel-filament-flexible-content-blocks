<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Overview\OverviewType;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\OverviewItemField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

    class OverviewBlock extends AbstractFilamentFlexibleContentBlock
    {
        public ?string $title;

        public array $items = [];

    private ?Collection $overviewItems;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

            $this->title = $blockData['title'] ?? null;
            $this->items = $blockData['items'] ?? null;
        }

        public static function getIcon(): string
        {
            return 'heroicon-o-view-list';
        }

    protected static function makeFilamentSchema(): array|Closure
    {
        $overviewModels = static::getOverviewModels();

        return [
            TextInput::make('title')
                ->label(self::getFieldLabel('title'))
                ->maxLength(255),
            Repeater::make('items')
                ->label(self::getFieldLabel('items'))
                ->schema([
                    OverviewItemField::make('overview_item')
                        ->types(collect(static::getOverviewModels())->map(fn ($item) => new OverviewType($item))->toArray()),
                ]),
        ];
    }

        public static function getNameSuffix(): string
        {
            return 'overview';
        }

    public static function getOverviewModels(): array
    {
        return config('filament-flexible-content-blocks.block_specific.'.self::class.'.overview_models',
            config('filament-flexible-content-blocks.overview_models', [])
        );
    }

    /**
     * @return Collection<HasOverviewAttributes>
     */
    public function getOverviewItems(): Collection
    {
        $models = self::getOverviewModels();
        $modelIds = collect($this->items)->mapToGroups(function ($item, $key) {
            return [$item['overview_model'] => $item['overview_id']];
        });
        $overviewItems = [];
        foreach ($models as $model) {
            $modelName = app($model)->getMorphClass();

            $overviewItems[$modelName] = app($model)::find($modelIds->get($modelName))->keyBy('id');
        }

        return collect($this->items)->map(function ($item) use ($overviewItems) {
            return $overviewItems[$item['overview_model']]->get($item['overview_id']);
        });
    }
}
