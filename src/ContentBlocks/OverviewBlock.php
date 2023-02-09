<?php

    namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

    use App\Models\Page;
    use Closure;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\MorphToSelect;
    use Filament\Forms\Components\Repeater;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\TextInput;
    use ReflectionClass;
    use Spatie\MediaLibrary\HasMedia;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;
    use Statikbe\FilamentFlexibleContentBlocks\Models\TranslatablePage;

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
            return [
                TextInput::make('title')
                    ->label(self::getFieldLabel('title'))
                    ->maxLength(255),
                Repeater::make('items')
                    ->label(self::getFieldLabel('items'))
                    ->schema([
                        Grid::make(6)
                            ->schema([
                                Select::make('model')
                                    ->label(self::getFieldLabel('item_model'))
                                    ->options(static::getOverviewModels())
                                    ->columnSpan(2),
                                Select::make('overview_item')
                                    ->label(self::getFieldLabel('item_id'))
                                    ->columnSpan(4),
                            ])
                    ]),
            ];
        }

        public static function getNameSuffix(): string {
            return 'overview';
        }

        private static function getOverviewModels(): array {
            $classes = get_declared_classes();
            $implementsOverview = array();
            foreach($classes as $klass) {
                $reflect = new ReflectionClass($klass);
                if($reflect->implementsInterface(HasOverviewAttributes::class))
                    $implementsOverview[] = $klass;
            }
            return $implementsOverview;
        }
    }
