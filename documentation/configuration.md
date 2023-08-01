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

## Image conversions

The default image conversions can be overwritten and new conversions can be added to the hero, overview and SEO
images, as well as to the images of flexible blocks.
Image conversions are set under the key 'models` and those of blocks are set under `flexible_blocks`. You can
overwrite the conversions of all models (key: `default`) or for a specific model by adding the model under key:
`specific`. First declare the image collection and then the conversion name. You can extend the already defined
conversions by adding a `extra_conversions` array to the collection name.

To define the conversions, you can use all the [spatie-image options](https://spatie.be/docs/image/v1/image-manipulations/overview) 
that can be configured as array keys. You can use any single argument functions, with the function name as key and
the argument as value. To resize, use `fit` with a `Spatie\Image\Manipulations::FIT_*` constante and define `width` and `height`.
You can also queue conversions (key: `queued`) and make responsive images (key: `responsive`) by setting the value to `true`.
Below is a detailed example:

```php 
'image_conversions' => [
    'models' => [
        'default' => [
            'seo_image' => [
                'seo_image' => [
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 1200,
                    'height' => 630,
                    'responsive' => true,
                ],
            ],
            'hero_image' => [
                'hero_image' => [
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 1200,
                    'height' => 630,
                    'responsive' => true,
                ],
                'extra_conversions' => [
                    'hero_image_square' => [
                        'fit' => Manipulations::FIT_CROP,
                        'width' => 400,
                        'height' => 400,
                        'responsive' => true,
                    ],
                ],
            ],
        ],
        'specific' => [
            /*Page::class => [
                'overview_image' => [
                    'thumb' => [
                        'fit' => Manipulations::FIT_CROP,
                        'width' => 400,
                        'height' => 400,
                        'responsive' => true,
                    ],
                ],
            ],*/
        ],
    ],
    'flexible_blocks' => [
        'default' => [],
        'specific' => [],
    ],
],
```

## Grid columns 

The allowed choices the user has to create columns in a grid. For instance, to list overview items in columns.
The maximum is 12 columns.

```php 
'grid_columns' => [
    1, 2, 3, 4,
],
```

## Background colour options

Some blocks allow to select a background colour to tweak the styling. You can define the options of the select form
component here. Each option has label with the translation key and the corresponding CSS class that will be applied.
In the 'default' key, you can set the default option.

```php
'background_colours' => [
    'options' => [
        'primary' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.primary',
            'class' => 'bg-primary-500 text-primary-contrast',
        ],
        'grey' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.grey',
            'class' => 'bg-slate-200',
        ],
    ],
    'default' => 'primary',
],
```

## Call-to-action button style options

CTA buttons can have different styles. You can define the options of the select form
component here. Each option has label with the translation key and the corresponding CSS class that will be applied. 
In the 'default' key, you can set the default option.

```php
'call_to_action_buttons' => [
    'options' => [
        'primary' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary',
            'class' => 'btn btn--primary',
        ],
        'primary_chevron' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary_chevron',
            'class' => 'btn btn--primary btn--ext',
        ],
        'ghost' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost',
            'class' => 'btn btn--ghost',
        ],
        'link' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link',
            'class' => 'link',
        ],
    ],
    'default' => 'primary',
],
```

## Image width options

Blocks with images can set the image's width. You can define the options of the select form
component here. Each option has label with the translation key and the corresponding CSS class that will be applied.
In the 'default' key, you can set the default option.

```php 
'image_width' => [
    'options' => [
        '100%' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.100%',
            'class' => 'w-full',
        ],
        '50%' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.50%',
            'class' => 'md:w-1/2',
        ],
        '25%' => [
            'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.25%',
            'class' => 'lg:w-1/4',
        ],
    ],
    'default' => '100%',
],
```

## Block style options

You can create different templates with a different layout or styling for a block. This configuration allows to 
define the styles, either for all blocks by setting 'enabled_for_all_blocks' to true and defining the options here.
The key is the suffix that will be applied to the block's blade template name. If you want to define styles for a 
specific block, please do it in [the 'block_specific' configuration](#block-specific-configuration).

```php 
'block_styles' => [
    'enabled_for_all_blocks' => true,
    'options' => [
        'default' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.block_styles.default',
    ],
    'default' => 'default',
],
```

## Overview models

List the models that can be used to add items from in the overview block.

```php 
'overview_models' => [
    //e.g. 'App\Models\FlexiblePage',
],
```

## Call-to-action models

List the models that can be used to link to with call-to-action buttons. The models should implement the Linkable interface.

```php 
'call_to_action_models' => [
    //e.g. 'App\Models\FlexiblePage',
],
```

## Allowed link routes

Here you can define which routes are shown in the select form component for call-to-action buttons.
By default all routes defined by your Laravel application are displayed.
You can either list the allowed or denied routes. You can use wildcards with `*`.

```php
'link_routes' => [
    'allowed' => [
        '*',
    ],
    'denied' => [
        'debugbar*',
        'filament.*',
        'livewire.*',
        'ignition.*',
        'api*',
        'login_authorize',
        'login_create',
    ],
],
```
