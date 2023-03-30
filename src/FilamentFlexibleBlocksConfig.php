<?php

namespace Statikbe\FilamentFlexibleContentBlocks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\HasMedia;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\OverviewBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TemplateBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes;

class FilamentFlexibleBlocksConfig
{
    /**
     * @return array<string, string>
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getLinkRoutes(): array
    {
        return Cache::rememberForever('filament-flexible-content-blocks::link_routes', function () {
            /* @var RouteCollection $routeCollection */
            $routeCollection = app()->get('router')->getRoutes()->getRoutes();
            //Default remove all routes with parameters and no GET requests:
            $filteredRoutes = collect($routeCollection)->filter(function (Route $route) {
                return collect($route->methods())->contains('GET') && empty($route->parameterNames());
            });

            //keep routes that match the allowed route parameters:
            $allowedRoutePatterns = config('filament-flexible-content-blocks.link_routes.allowed');
            if (! empty($allowedRoutePatterns)) {
                $filteredRoutes = $filteredRoutes->filter(function (Route $route) use ($allowedRoutePatterns) {
                    return self::matchRouteToPatterns($route, $allowedRoutePatterns);
                });
            }

            //remove routes that match the allowed route parameters:
            $disallowedRoutePatterns = config('filament-flexible-content-blocks.link_routes.denied');
            if (! empty($disallowedRoutePatterns)) {
                $filteredRoutes = $filteredRoutes->filter(function (Route $route) use ($disallowedRoutePatterns) {
                    return ! self::matchRouteToPatterns($route, $disallowedRoutePatterns);
                });
            }

            return $filteredRoutes->mapWithKeys(function ($route) {
                if ($route->getName()) {
                    return [$route->getName() => $route->uri()];
                }

                return [];
            })->all();
        });
    }

    private static function matchRouteToPatterns(Route $route, array $patterns): bool
    {
        if ($route->getName()) {
            return $route->named($patterns);
        } else {
            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $route->uri())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  class-string<Model>  $modelClass
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public static function mergeConfiguredModelImageConversion(string $modelClass, string $collectionName, string $conversionName, Conversion &$conversion): Conversion
    {
        $configuredConversions = self::getModelImageConversionConfig($modelClass);

        return self::mergeConfiguredImageConversions($configuredConversions, $collectionName, $conversionName, $conversion);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public static function mergeConfiguredFlexibleBlockImageConversion(string $blockClass, string $collectionName, string $conversionName, Conversion &$conversion): Conversion
    {
        $configuredConversions = self::getFlexibleBlockImageConversionConfig($blockClass);

        return self::mergeConfiguredImageConversions($configuredConversions, $collectionName, $conversionName, $conversion);
    }

    /**
     * @param  array<string, array>  $configuredConversions
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    private static function mergeConfiguredImageConversions(array $configuredConversions, string $collectionName, string $conversionName, Conversion &$conversion): Conversion
    {
        if (isset($configuredConversions[$collectionName][$conversionName])) {
            $configuredConversion = $configuredConversions[$collectionName][$conversionName];
            self::mergeConfiguredImageConversion($configuredConversion, $conversion);
        }

        return $conversion;
    }

    private static function mergeConfiguredImageConversion(array $configuredConversion, Conversion &$conversion): Conversion
    {
        $alreadyDoneConversions = [];
        //specific cases for specific method calls on the conversion object:
        if (isset($configuredConversion['fit']) && isset($configuredConversion['width']) && isset($configuredConversion['height'])) {
            $conversion->fit($configuredConversion['fit'], $configuredConversion['width'], $configuredConversion['height']);
            $alreadyDoneConversions = ['fit', 'width', 'height'];
        }
        if (isset($configuredConversion['responsive']) && $configuredConversion['responsive']) {
            $conversion->withResponsiveImages();
            $alreadyDoneConversions[] = 'responsive';
        }
        if (isset($configuredConversion['queued']) && $configuredConversion['queued']) {
            $conversion->queued();
            $alreadyDoneConversions[] = 'queued';
        }
        //do the manipulations that are left:
        foreach ($configuredConversion as $operation => $value) {
            if (! in_array($operation, $alreadyDoneConversions)) {
                $conversion->{$operation}($value);
            }
        }

        return $conversion;
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function addExtraFlexibleBlockImageConversions(string $blockClass, string $collectionName, Model&HasMedia $record): void
    {
        $configuredConversionData = self::getFlexibleBlockImageConversionConfig($blockClass);
        self::addExtraImageConversions($configuredConversionData, $collectionName, $record);
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    public static function addExtraModelImageConversions(string $modelClass, string $collectionName, Model&HasMedia $record): void
    {
        $configuredConversionData = self::getModelImageConversionConfig($modelClass);
        self::addExtraImageConversions($configuredConversionData, $collectionName, $record);
    }

    /**
     * @param  array<string, array>  $configuredConversions
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    private static function addExtraImageConversions(array $configuredConversions, string $collectionName, Model&HasMedia $record): void
    {
        if (isset($configuredConversions[$collectionName]['extra_conversions'])) {
            foreach ($configuredConversions[$collectionName]['extra_conversions'] as $extraConversionName => $extraConversionData) {
                $conversion = $record->addMediaConversion($extraConversionName);
                self::mergeConfiguredImageConversion($configuredConversions[$collectionName]['extra_conversions'][$extraConversionName], $conversion);
            }
        }
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    public static function getModelImageConversionConfig(string $modelClass): array
    {
        return config('filament-flexible-content-blocks.image_conversions.models.specific.'.$modelClass,
            config('filament-flexible-content-blocks.image_conversions.models.default', [])
        );
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getFlexibleBlockImageConversionConfig(string $blockClass): array
    {
        return config('filament-flexible-content-blocks.image_conversions.flexible_blocks.specific.'.$blockClass,
            config('filament-flexible-content-blocks.image_conversions.flexible_blocks.default', [])
        );
    }

    /**
     * @return array<class-string<AbstractContentBlock>>
     */
    public static function getDefaultFlexibleBlocks(): array
    {
        return config('filament-flexible-content-blocks.default_flexible_blocks', [
            TextBlock::class,
            VideoBlock::class,
            ImageBlock::class,
            HtmlBlock::class,
            TextImageBlock::class,
            OverviewBlock::class,
            QuoteBlock::class,
            CallToActionBlock::class,
            CardsBlock::class,
            TemplateBlock::class,
        ]);
    }

    public static function getTheme(): ?string
    {
        return config('filament-flexible-content-blocks.theme', 'tailwind');
    }

    public static function getViewThemePrefix(): string
    {
        $theme = FilamentFlexibleBlocksConfig::getTheme();
        return $theme ? $theme.'.': '';
    }

    public static function getAuthorModel(): string
    {
        return config('filament-flexible-content-blocks.author_model', 'Illuminate\Foundation\Auth\User');
    }

    public static function getPublishingDateFormatting(): string
    {
        return config('filament-flexible-content-blocks.formatting.publishing_dates', 'd/m/Y G:i');
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array<class-string<HasOverviewAttributes>>
     */
    public static function getOverviewModels(string $blockClass): array
    {
        return config('filament-flexible-content-blocks.block_specific.'.$blockClass.'.overview_models',
            config('filament-flexible-content-blocks.overview_models', [])
        );
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array<class-string>
     */
    public static function getCallToActionModels(string $blockClass): array
    {
        return config('filament-flexible-content-blocks.block_specific.'.$blockClass.'.call_to_action_models',
            config('filament-flexible-content-blocks.call_to_action_models', [])
        );
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getTemplatesConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'templates');
    }

    public static function getTemplatesSelectOptions(string $blockClass): array
    {
        $config = self::getTemplatesConfig($blockClass);

        return collect($config)
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item)])
            ->toArray();
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return int[]
     */
    public static function getGridColumnsConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'grid_columns');
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array<int, int>
     */
    public static function getGridColumnsSelectOptions(string $blockClass): array
    {
        return collect(self::getGridColumnsConfig($blockClass))
            ->mapWithKeys(fn ($item, $key) => [$item => $item])
            ->toArray();
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array{options?: array<string, array{label: string, class: string}>, default?: string}
     */
    public static function getImageWidthConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'image_width');
    }

    /**
     * @return array<string, string>
     */
    public static function getImageWidthSelectOptions(string $blockClass): array
    {
        return self::getSelectOptions(self::getImageWidthConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getImageWidthClass(string $blockClass, ?string $widthType): ?string
    {
        return self::getCssClass(self::getImageWidthConfig($blockClass), $widthType);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getImageWidthDefault(string $blockClass): ?string
    {
        return self::getDefault(self::getImageWidthConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array{options?: array<string, string>, default?: string, enabled_for_all_blocks?: bool}
     */
    public static function getBlockStyleConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'block_styles');
    }

    /**
     * @return array<string, string>
     */
    public static function getBlockStyleSelectOptions(string $blockClass): array
    {
        return collect(self::getBlockStyleConfig($blockClass)['options'])
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item)])
            ->toArray();
    }

    public static function isBlockStyleEnabled(string $blockClass): bool
    {
        $blockConfig = self::getBlockStyleConfig($blockClass);
        if (isset($blockConfig['enabled'])) {
            return $blockConfig['enabled'];
        }

        return config('filament-flexible-content-blocks.block_styles.enabled_for_all_blocks', false);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getBlockStyleDefault(string $blockClass): ?string
    {
        return self::getDefault(self::getBlockStyleConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array{options?: array<string, array{label: string, class: string}>, default?: string}
     */
    public static function getImagePositionConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'image_position');
    }

    /**
     * @return array<string, string>
     */
    public static function getImagePositionSelectOptions(string $blockClass): array
    {
        return collect(self::getImagePositionConfig($blockClass)['options'])
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item)])
            ->toArray();
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getImagePositionDefault(string $blockClass): ?string
    {
        return self::getDefault(self::getImagePositionConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array{options?: array<string, array{label: string, class: string}>, default?: string}
     */
    public static function getBackgroundColoursConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'background_colours');
    }

    /**
     * @return array<string, string>
     */
    public static function getBackgroundColoursSelectOptions(string $blockClass): array
    {
        return self::getSelectOptions(self::getBackgroundColoursConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getBackgroundColourClass(string $blockClass, ?string $colourType): ?string
    {
        return self::getCssClass(self::getBackgroundColoursConfig($blockClass), $colourType);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getBackgroundColourDefault(string $blockClass): ?string
    {
        return self::getDefault(self::getBackgroundColoursConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     * @return array{options?: array<string, array{label: string, class: string}>, default?: string}
     */
    public static function getCallToActionButtonsConfig(string $blockClass): array
    {
        return self::getConfig($blockClass, 'call_to_action_buttons');
    }

    /**
     * @return array<string, string>
     */
    public static function getCallToActionButtonsSelectOptions(string $blockClass): array
    {
        return self::getSelectOptions(self::getCallToActionButtonsConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getCallToActionButtonClass(string $blockClass, ?string $buttonType): ?string
    {
        return self::getCssClass(self::getCallToActionButtonsConfig($blockClass), $buttonType);
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    public static function getCallToActionButtonDefault(string $blockClass): ?string
    {
        return self::getDefault(self::getCallToActionButtonsConfig($blockClass));
    }

    /**
     * @param  class-string<AbstractContentBlock>  $blockClass
     */
    private static function getConfig(string $blockClass, string $configField): array
    {
        return config("filament-flexible-content-blocks.block_specific.$blockClass.$configField",
            config("filament-flexible-content-blocks.$configField", [])
        );
    }

    /**
     * @param  array{options?: array<string, array{label: string, class: string}>, default?: string}  $config
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
     * @param  array{options?: array<string, array{label: string, class: string}>, default?: string}  $config
     */
    private static function getDefault(array $config): ?string
    {
        return $config['default'] ?? null;
    }

    /**
     * @param  array{options?: array<string, array{label: string, class: string}>, default?: string}  $config
     * @return array<string, string>
     */
    private static function getSelectOptions(array $config): array
    {
        return collect($config['options'] ?? [])
            ->mapWithKeys(fn ($item, $key) => [$key => trans($item['label'])])
            ->toArray();
    }
}
