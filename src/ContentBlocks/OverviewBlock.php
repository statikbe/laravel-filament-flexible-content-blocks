<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

    use Closure;
    use Filament\Facades\Filament;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use Illuminate\Support\Str;
    use Spatie\MediaLibrary\HasMedia;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

    class OverviewBlock extends AbstractFilamentFlexibleContentBlock {

        public ?string $title;

        public array $items = [];

        public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData) {
            parent::__construct($record, $blockData);

            $this->title = $blockData['title'] ?? null;
            $this->items = $blockData['items'] ?? null;
        }

        public static function getIcon(): string {
            return 'heroicon-o-view-list';
        }

        protected static function makeFilamentSchema(): array|Closure {
            $overviewModels = static::getOverviewModels();
            $overviewModelOptions = collect($overviewModels)->mapWithKeys(function($item, $key){
                //make label based on configurable filament resource model name:
                return [$item => Str::ucfirst(Filament::getModelResource($item)::getModelLabel() ?? "Define $item resource model label")];
            });

            return [
                TextInput::make('title')
                    ->label(self::getFieldLabel('title'))
                    ->maxLength(255),
                Repeater::make('items')
                    ->label(self::getFieldLabel('items'))
                    ->schema([
                        Grid::make(6)
                            ->schema([
                                Select::make('overview_model')
                                    ->label(self::getFieldLabel('overview_model'))
                                    ->options($overviewModelOptions)
                                    ->columnSpan(2),
                                Select::make('overview_item')
                                    ->label(self::getFieldLabel('overview_item'))
                                    ->columnSpan(4),
                            ])
                    ]),
            ];
        }

        public static function getNameSuffix(): string {
            return 'overview';
        }

        public static function getOverviewModels(): array {
            return config("filament-flexible-content-blocks.block_specific." . self::class . ".overview_models",
                config('filament-flexible-content-blocks.overview_models', [])
            );
        }
    }
