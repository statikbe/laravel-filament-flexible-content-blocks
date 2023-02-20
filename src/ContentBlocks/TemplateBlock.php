<?php

namespace Statikbe\FilamentFlexibleContentBlocks\ContentBlocks;

    use Closure;
    use Filament\Forms\Components\Select;
    use Spatie\MediaLibrary\HasMedia;
    use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
    use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;

    class TemplateBlock extends AbstractFilamentFlexibleContentBlock
    {
        const FIELD_TEMPLATE = 'template';

        public ?string $template;

        public function __construct(HasMedia&HasContentBlocks $record, ?array $blockData)
        {
            parent::__construct($record, $blockData);

            $this->template = $blockData[self::FIELD_TEMPLATE] ?? null;
        }

        public static function getIcon(): string
        {
            return 'heroicon-o-template';
        }

        protected static function makeFilamentSchema(): array|Closure
        {
            return [
                Select::make(self::FIELD_TEMPLATE)
                    ->label(self::getFieldLabel(self::FIELD_TEMPLATE))
                    ->options(FilamentFlexibleBlocksConfig::getTemplatesSelectOptions(self::class)),
            ];
        }

        public static function getNameSuffix(): string
        {
            return 'template';
        }

        public static function visible(): bool|Closure
        {
            //only show block when templates are set in the config:
            return ! empty(FilamentFlexibleBlocksConfig::getTemplatesConfig(self::class));
        }
    }
