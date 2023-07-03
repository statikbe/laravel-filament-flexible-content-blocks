# Configuration 

## Supported locales

Since Laravel does not have any standard configuration to set the supported locales of your application, you can set them
with the `supportedLocales` variable:

```php
'supported_locales' => [
    'nl',
    'en',
],
```

Alternatively, you can also set the supported locales in a service provider, similar to the [Laravel Chained Translation Manager](https://github.com/statikbe/laravel-filament-chained-translation-manager). 
Set in the `boot` function of a service provider:

```php 
public function boot()
{
   FilamentFlexibleContentBlocks::setLocales(['en', 'nl']);
}
```

## Default flexible blocks

You can configure the default list of flexible content blocks that will be applied to all models with the [HasContentBlocksTrait.php](..%2Fsrc%2FModels%2FConcerns%2FHasContentBlocksTrait.php).

```php 
'default_flexible_blocks' => [
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
],
```

If you want to customise the flexible block list on a model, you can overwrite the `getFilamentContentBlocks()` function in your model.

## Frontend theme

It is possible to create different themes for the views of the blocks and their components.
Creating a new theme is done by publishing the views (see README.md) and then renaming the `tailwind` directory
to your theme name, e.g. `bootstrap`. You should then specify in the var below the name of your new theme.

```php 
'theme' => 'tailwind',
```

## Templates for template block

You need to list the Blade templates that are available in the dropdown in the template block. The template block enables 
you to include a Blade template on the frontend page.

The list consists of the name of the Blade template as key and the translation key that will be shown in the dropdown.

```php
'templates' => [
'partials.footer-nav' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.templates_options.footer',
],
```

## Block specific configuration

All configuration parameters related to blocks can be customised per block class. You can use all configuration
parameters related to blocks used above, to tweak the behaviour of a block class. Below are a few examples: 

```php
'block_specific' => [
    //Examples:
    TextImageBlock::class => [
        'image_position' => [
            'options' => [
                'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
                'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
            ],
            'default' => 'left',
        ],
    ],
    TextBlock::class => [
        'block_styles' => [
            'enabled' => true,
            'options' => [
                'default' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.block_styles.default',
                'better' => 'better',
                'nice' => 'nice',
            ],
        ],
    ],
],
```

## String formatting

You can set the date formatting of the publishing dates. Formatting parameters are compatible with [Carbon](https://carbon.nesbot.com/docs/#api-formatting).

```php
'formatting' => [
    'publishing_dates' => 'd/m/Y G:i',
],
```

## Author model

In case you have overwritten the default User model, you need to add the new class here to create the author relationship.

```php 
'author_model' => 'App\Models\User',
```

## Text parameter replacer

You can also integrate this feature in the Laravel translations to always have the parameters available in any translation
string. [Follow this how-to](text-parameter-replacer.md).
