<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

    class FilamentFlexibleBlocksConfig
    {
        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return array{options: array<string, array{label: string, class: string}>, default: string}
         */
        public static function getImageWidthConfig(string $blockClass): array
        {
            return config("filament-flexible-content-blocks.block_specific.$blockClass.image_width",
                config('filament-flexible-content-blocks.image_width', [])
            );
        }

        /**
         * @param  string  $blockClass
         * @return array<string, string>
         */
        public static function getImageWidthSelectOptions(string $blockClass): array
        {
            return self::getSelectOptions(self::getImageWidthConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @param  string|null  $widthType
         * @return string|null
         */
        public static function getImageWidthClass(string $blockClass, ?string $widthType): ?string
        {
            return self::getCssClass(self::getImageWidthConfig($blockClass), $widthType);
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return string|null
         */
        public static function getImageWidthDefault(string $blockClass): ?string
        {
            return self::getDefault(self::getImageWidthConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return array{options: array<string, array{label: string, class: string}>, default: string}
         */
        public static function getImagePositionConfig(string $blockClass): array
        {
            return config("filament-flexible-content-blocks.block_specific.$blockClass.image_position",
                config('filament-flexible-content-blocks.image_position', [])
            );
        }

        /**
         * @param  string  $blockClass
         * @return array<string, string>
         */
        public static function getImagePositionSelectOptions(string $blockClass): array
        {
            return self::getSelectOptions(self::getImagePositionConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @param  string|null  $positionType
         * @return string|null
         */
        public static function getImagePositionClass(string $blockClass, ?string $positionType): ?string
        {
            return self::getCssClass(self::getImagePositionConfig($blockClass), $positionType);
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return string|null
         */
        public static function getImagePositionDefault(string $blockClass): ?string
        {
            return self::getDefault(self::getImagePositionConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return array{options: array<string, array{label: string, class: string}>, default: string}
         */
        public static function getBackgroundColoursConfig(string $blockClass): array
        {
            return config("filament-flexible-content-blocks.block_specific.$blockClass.background_colours",
                config('filament-flexible-content-blocks.background_colours', [])
            );
        }

        /**
         * @param  string  $blockClass
         * @return array<string, string>
         */
        public static function getBackgroundColoursSelectOptions(string $blockClass): array
        {
            return self::getSelectOptions(self::getBackgroundColoursConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @param  string|null  $colourType
         * @return string|null
         */
        public static function getBackgroundColourClass(string $blockClass, ?string $colourType): ?string
        {
            return self::getCssClass(self::getBackgroundColoursConfig($blockClass), $colourType);
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return string|null
         */
        public static function getBackgroundColourDefault(string $blockClass): ?string
        {
            return self::getDefault(self::getBackgroundColoursConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return array{options: array<string, array{label: string, class: string}>, default: string}
         */
        public static function getCallToActionButtonsConfig(string $blockClass): array
        {
            return config("filament-flexible-content-blocks.block_specific.$blockClass.call_to_action_buttons",
                config('filament-flexible-content-blocks.call_to_action_buttons', [])
            );
        }

        /**
         * @param  string  $blockClass
         * @return array<string, string>
         */
        public static function getCallToActionButtonsSelectOptions(string $blockClass): array
        {
            return self::getSelectOptions(self::getCallToActionButtonsConfig($blockClass));
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @param  string|null  $buttonType
         * @return string|null
         */
        public static function getCallToActionButtonClass(string $blockClass, ?string $buttonType): ?string
        {
            return self::getCssClass(self::getCallToActionButtonsConfig($blockClass), $buttonType);
        }

        /**
         * @param  class-string<AbstractContentBlock>  $blockClass
         * @return string|null
         */
        public static function getCallToActionButtonDefault(string $blockClass): ?string
        {
            return self::getDefault(self::getCallToActionButtonsConfig($blockClass));
        }

        /**
         * @param  array{options: array<string, array{label: string, class: string}>, default: string}  $config
         * @param  string|null  $type
         * @return string|null
         */
        private static function getCssClass(array $config, ?string $type): ?string
        {
            if (! $type) {
                $type = self::getDefault($config);
            }

            $classMap = collect($config['options'] ?? [])
                ->mapWithKeys(fn ($item, $key) => [$key => trans($item['class'])]);

            return $classMap[$type] ?? null;
        }

        /**
         * @param  array{options: array<string, array{label: string, class: string}>, default: string}  $config
         * @return string|null
         */
        private static function getDefault(array $config): ?string
        {
            return $config['default'] ?? null;
        }

        /**
         * @param  array{options: array<string, array{label: string, class: string}>, default: string}  $config
         * @return array<string, string>
         */
        private static function getSelectOptions(array $config): array
        {
            return collect($config['options'] ?? [])
                ->mapWithKeys(fn ($item, $key) => [$key => trans($item['label'])])
                ->toArray();
        }
    }
