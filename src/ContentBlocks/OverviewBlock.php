<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasBackgroundColour;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\BackgroundColourField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\GridColumnsField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\OverviewItemField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Type\OverviewType;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

class OverviewBlock extends AbstractFilamentFlexibleContentBlock
{
    use HasBackgroundColour;

    public ?string $title;

    public array $items = [];

    public int $gridColumns = 3;

    public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
    {
        parent::__construct($record, $blockData);

        $this->title = $blockData['title'] ?? null;
        $this->items = $blockData['items'] ?? null;
        $this->backgroundColourType = $blockData['background_colour'] ?? null;
        $this->gridColumns = $blockData['grid_columns'] ?? null;
    }

        public static function getIcon(): string
        {
            return 'heroicon-o-collection';
        }

    protected static function makeFilamentSchema(): array|Closure
    {
        $overviewItemField = OverviewItemField::make('overview_item')
            ->types(collect(FilamentFlexibleBlocksConfig::getOverviewModels(self::class))->map(fn ($item) => new OverviewType($item))->toArray());

        return [
            TextInput::make('title')
                ->label(self::getFieldLabel('title'))
                ->maxLength(255),
            Grid::make(2)->schema([
                BackgroundColourField::create(self::class),
                GridColumnsField::create(self::class, true),
            ]),
            Repeater::make('items')
                ->label(self::getFieldLabel('items'))
                ->schema([
                    $overviewItemField,
                ])
                ->itemLabel(function (array $state) use ($overviewItemField): ?string {
                    if ($state['overview_model'] && $state['overview_id'] && $overviewItemField->getTypes()[$state['overview_model']]) {
                        return $overviewItemField->getTypes()[$state['overview_model']]->getLabel().' #'.$state['overview_id'];
                    } else {
                        return null;
                    }
                })
                ->collapsible()
                ->minItems(1),
        ];
    }

        public static function getNameSuffix(): string
        {
            return 'overview';
        }

    /**
     * @return Collection<HasOverviewAttributes>
     */
    public function getOverviewItems(): Collection
    {
        $models = FilamentFlexibleBlocksConfig::getOverviewModels(self::class);
        $modelIds = collect($this->items)->mapToGroups(function ($item, $key) {
            return [$item['overview_model'] => $item['overview_id']];
        });
        $overviewItems = [];
        foreach ($models as $model) {
            $modelName = app($model)->getMorphClass();

            if ($modelIds->has($modelName)) {
                $overviewItems[$modelName] = app($model)::find($modelIds->get($modelName))->keyBy('id');
            }
        }

        return collect($this->items)->map(function ($item) use ($overviewItems) {
            return $overviewItems[$item['overview_model']]->get($item['overview_id']);
        });
    }
}
