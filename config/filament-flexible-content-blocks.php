<?php

// config for Statikbe/FilamentFlexibleContentBlocks
use Spatie\Image\Manipulations;
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

return [
    /*
    |--------------------------------------------------------------------------
    | Application Supported Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the possible locales that can be used.
    | You are free to fill this array with any of the locales which will be
    | supported by the application.
    |
    */
    'supported_locales' => [
        'nl',
        'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default flexible blocks
    |--------------------------------------------------------------------------
    |
    | The list of default blocks that are available in all models with the HasContentBlocksTrait.
    |
    */
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

    /*
     |--------------------------------------------------------------------------
     | Frontend theme
     |--------------------------------------------------------------------------
     |
     | It is possible to create different themes for the views of the blocks and their components.
     | Creating a new theme is done by publishing the views (see README.md) and then renaming the `tailwind` directory
     | to your theme name, e.g. `bootstrap`. You should then specify in the var below the name of your new theme.
     */
    'theme' => 'tailwind',

    /*
     |--------------------------------------------------------------------------
     | Image conversions
     |--------------------------------------------------------------------------
     |
     | The default image conversions can be overwritten and new conversions can be added to the hero, overview and SEO
     | images, as well as to the images of flexible blocks.
     | Image conversions are set under the key 'models` and those of blocks are set under 'flexible_blocks'. You can
     | overwrite the conversions of all models (key: 'default') or for a specific model by adding the model under key:
     | 'specific'. First declare the image collection and then the conversion name. You can extend the already defined
     | conversions by adding a 'extra_conversions' array to the collection name.
     |
     | To define the conversions, you can use all the spatie-image options that can be configured as array keys.
     */
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
                'overview_image' => [
                    'overview_image' => [
                        'fit' => Manipulations::FIT_CROP,
                        'width' => 500,
                        'height' => 500,
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

    /*
    |--------------------------------------------------------------------------
    | Image editor
    |--------------------------------------------------------------------------
    |
    | Enables the image editor. You can set preset aspect ratios, the editor mode of Cropper.js, and the default
    | viewport size.
    | see https://filamentphp.com/docs/3.x/forms/fields/file-upload#setting-the-image-editors-mode
    */
    'image_editor' => [
        'enabled' => false,
        'aspect_ratios' => [
            null,
            '16:9',
            '4:3',
            '1:1',
        ],
        'mode' => 1, // see https://github.com/fengyuanchen/cropperjs#viewmode
        'empty_fill_colour' => null,  // e.g. #000000
        'viewport' => [
            'width' => 1920,
            'height' => 1080,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Overview models
    |--------------------------------------------------------------------------
    |
    | List the models that can be used to add items from in the overview block.
    */
    'overview_models' => [
        //e.g. 'App\Models\FlexiblePage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Call-to-action models
    |--------------------------------------------------------------------------
    |
    | List the models that can be used to link to with call-to-action buttons.
    */
    'call_to_action_models' => [
        //e.g. 'App\Models\FlexiblePage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed link routes
    |--------------------------------------------------------------------------
    |
    | Here you can define which routes are shown in the select form component for call-to-action buttons.
    | By default all routes defined by your Laravel application are displayed.
    | You can either list the allowed or denied routes. You can use wildcards with `*`.
    */
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

    /*
    |--------------------------------------------------------------------------
    | Image position options
    |--------------------------------------------------------------------------
    |
    | Blocks with images can define where the image is positioned in its container. You can define the options of the select form
    | component here. Each option has the direction as key and the translation key as value.
    | In the 'default' key, you can set the default option.
    */
    'image_position' => [
        'options' => [
            'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
            'center' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.center',
            'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
        ],
        'default' => 'left',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image width options
    |--------------------------------------------------------------------------
    |
    | Blocks with images can set the image's width. You can define the options of the select form
    | component here. Each option has label with the translation key and the corresponding CSS class that will be applied.
    | In the 'default' key, you can set the default option.
    */
    'image_width' => [
        'options' => [
            '100%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.100%',
                'class' => 'w-full',
            ],
            '75%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.75%',
                'class' => 'md:w-3/4',
            ],
            '50%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.50%',
                'class' => 'md:w-1/2',
            ],
            '33%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.33%',
                'class' => 'lg:w-1/3',
            ],
            '25%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.25%',
                'class' => 'lg:w-1/4',
            ],
        ],
        'default' => '100%',
    ],

    /*
    |--------------------------------------------------------------------------
    | Call-to-action button style options
    |--------------------------------------------------------------------------
    |
    | CTA buttons can have different styles. You can define the options of the select form
    | component here. Each option has label with the translation key and the corresponding CSS class that will be applied.
    | In the 'default' key, you can set the default option.
    */
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
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary',
                'class' => 'btn btn--secondary',
            ],
            'secondary_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary_chevron',
                'class' => 'btn btn--secondary btn--ext',
            ],
            'ghost' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost',
                'class' => 'btn btn--ghost',
            ],
            'ghost_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost_chevron',
                'class' => 'btn btn--ghost btn--ext',
            ],
            'link' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link',
                'class' => 'link',
            ],
            'link_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link_chevron',
                'class' => 'link link--ext',
            ],
        ],
        'default' => 'primary',
    ],

    /*
    |--------------------------------------------------------------------------
    | Background colours options
    |--------------------------------------------------------------------------
    |
    | Some blocks allow to select a background colour to tweak the styling. You can define the options of the select form
    | component here. Each option has label with the translation key and the corresponding CSS class that will be applied.
    | In the 'default' key, you can set the default option.
    */
    'background_colours' => [
        'options' => [
            'default' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.default',
                'class' => '',
            ],
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.primary',
                'class' => 'bg-primary-500 text-primary-contrast',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.secondary',
                'class' => 'bg-secondary text-secondary-contrast',
            ],
            'grey' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.grey',
                'class' => 'bg-slate-200',
            ],
        ],
        'default' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Block style options
    |--------------------------------------------------------------------------
    |
    | You can create different templates with a different layout or styling for a block. This configuration allows to
    | define the styles, either for all blocks by setting 'enabled_for_all_blocks' to true and defining the options here.
    | The key is the suffix that will be applied to the block's blade template name. If you want to define styles for a
    | specific block, please do it in the 'block_specific' configuration.
    */
    'block_styles' => [
        'enabled_for_all_blocks' => true,
        'options' => [
            'default' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.block_styles.default',
        ],
        'default' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Grid columns
    |--------------------------------------------------------------------------
    |
    | The allowed choices the user has to create columns in a grid. For instance, to list overview items in columns.
    | Maximum is 12 columns.
    */
    'grid_columns' => [
        1, 2, 3, 4,
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates for template block
    |--------------------------------------------------------------------------
    |
    | You need to list the Blade templates that are available in the dropdown in the template block.
    | The list consists of the name of the Blade template as key and the translation key that will be shown in the dropdown.
    */
    'templates' => [
        'partials.footer-nav' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.templates_options.footer',
    ],

    /*
    |--------------------------------------------------------------------------
    | String formatting
    |--------------------------------------------------------------------------
    |
    | You can set the date formatting of the publishing dates. Formatting parameters are compatible with Carbon, https://carbon.nesbot.com/docs/#api-formatting
    */
    'formatting' => [
        'publishing_dates' => 'd/m/Y G:i',
    ],

    /*
    |--------------------------------------------------------------------------
    | Author model
    |--------------------------------------------------------------------------
    |
    | In case you have overwritten the default User model, you need to add the new class here to create the author relationship.
    */
    'author_model' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Block specific configuration
    |--------------------------------------------------------------------------
    |
    | All configuration parameters related to blocks can be customised per block class. You can use all configuration
    | parameters used above to customise a block class.
    */
    'block_specific' => [
        /*
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
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Text parameter replacer
    |--------------------------------------------------------------------------
    |
    | Within the text fields of content blocks, you can replace parameters prefixed by a colon (e.g. :name), identical
    | to the way parameters work in translations. The replacer class implements the
    | Statikbe\FilamentFlexibleContentBlocks\Replacer\TextParameterReplacer interface that returns an array
    | of parameters with their value that will be replaced in the text fields of the content blocks.
    |
    | The value of `text_parameter_replacer` should be class name or null. If null is set, parameter replacement is disabled.
     */
    'text_parameter_replacer' => null,
];
