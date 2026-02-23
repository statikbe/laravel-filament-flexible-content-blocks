<?php

// config for Statikbe/FilamentFlexibleContentBlocks
use Spatie\Image\Enums\Fit;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CollapsibleGroupBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\OverviewBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TemplateBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\RichEditorConfigurator\DefaultRichEditorConfigurator;

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
        VideoBlock::class,
        ImageBlock::class,
        HtmlBlock::class,
        TextImageBlock::class,
        OverviewBlock::class,
        QuoteBlock::class,
        CallToActionBlock::class,
        CardsBlock::class,
        TemplateBlock::class,
        CollapsibleGroupBlock::class,
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
                        'fit' => Fit::Crop,
                        'width' => 900,
                        'height' => 630,
                        'responsive' => true,
                    ],
                ],
                'overview_image' => [
                    'overview_image' => [
                        'fit' => Fit::Crop,
                        'width' => 500,
                        'height' => 500,
                        'responsive' => true,
                    ],
                ],
                'hero_image' => [
                    'hero_image' => [
                        'fit' => Fit::Crop,
                        'width' => 1200,
                        'height' => 630,
                        'responsive' => true,
                    ],
                    'extra_conversions' => [
                        'hero_image_square' => [
                            'fit' => Fit::Crop,
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
                            'fit' => Fit::Crop,
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
    | Rich editor
    |--------------------------------------------------------------------------
    |
    | Configure the rich text editor used in content blocks. You can swap the editor component by providing
    | a custom configurator class that implements the RichEditorConfigurator interface.
    | For example, to use the TipTap editor, create a configurator that returns a TiptapEditor instance.
    |
    | Toolbar buttons can be configured globally here, or per block in the 'block_specific' section.
    |
    | - toolbar_buttons: A positive list of buttons to enable. When set, only these buttons are shown.
    | - disabled_toolbar_buttons: A list of buttons to disable from the editor defaults.
    |
    | If both are null, the editor defaults are used with 'attachFiles' disabled.
    | If toolbar_buttons is set, disabled_toolbar_buttons is ignored.
    */
    'rich_editor' => [
        'configurator' => DefaultRichEditorConfigurator::class,
        'toolbar_buttons' => null,
        'disabled_toolbar_buttons' => [
            'attachFiles',
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
        // e.g. 'App\Models\FlexiblePage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Call-to-action models
    |--------------------------------------------------------------------------
    |
    | List the models that can be used to link to with call-to-action buttons.
    | Or in case you want to extend the CTA types, you can also add an array with keys `model` and `call_to_action_type`,
    | to which you can respectively add the model and its the custom CTA type implementation.
    */
    'call_to_action_models' => [
        // e.g. 'App\Models\FlexiblePage',
        // Or if you want to implement a custom CTA type, e.g. for the asset manager see https://github.com/statikbe/laravel-filament-flexible-blocks-asset-manager:
        /*[
            'model' => \Statikbe\FilamentFlexibleBlocksAssetManager\Models\Asset::class,
            'call_to_action_type' => \Statikbe\FilamentFlexibleBlocksAssetManager\Filament\Form\Fields\Blocks\Type\AssetCallToActionType::class,
        ],*/
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
            '*livewire.*',
            'ignition.*',
            '*debug*',
            'api*',
            'login_authorize',
            'login_create',
            'google.auth.login',
            'filament-impersonate.*',
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
                'class' => 'w-full md:w-3/4',
            ],
            '50%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.50%',
                'class' => 'w-full md:w-1/2',
            ],
            '33%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.33%',
                'class' => 'w-full lg:w-1/3',
            ],
            '25%' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_width.25%',
                'class' => 'w-full lg:w-1/4',
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
    | component here. Each option has a label with the translation key and the corresponding CSS class(es) that will be applied.
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
    | Call-to-action number of items validation
    |--------------------------------------------------------------------------
    |
    | Some blocks allow adding call-to-action items. You can define the default min- and max number of items here.
    | If needed, you can overrule the default min/max for a particular block in the 'block_specific' configuration.
    */
    'call_to_action_number_of_items' => [
        'min' => 0,
        'max' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Background colours options
    |--------------------------------------------------------------------------
    |
    | Some blocks allow to select a background colour to tweak the styling. You can define the options of the select form
    | component here. Each option has a label with the translation key and the corresponding CSS class(es) that will be applied.
    | In the 'default' key, you can set the default option.
    */
    'background_colours' => [
        'options' => [
            'default' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.default',
                'class' => 'content-block_bg--default',
            ],
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.primary',
                'class' => 'content-block_bg--primary',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.secondary',
                'class' => 'content-block_bg--secondary',
            ],
            'light' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.light',
                'class' => 'content-block_bg--light',
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
     | Blocks wrapper styling
     |--------------------------------------------------------------------------
     |
     | To tweak the display, within the admin section, of the configured content blocks for a page.
     | The default configured classes below - which you can overrule - will:
     | - improve the spacing between the configured content blocks
     | - improve the contrast between the content blocks by adding background colors
     | If you do not want to alter the styling of the blocks, you can just leave the array empty.
     | (PS: The classes are organized in an array for better readability so that you can group related classes)
     */
    'admin_blocks_wrapper_classes' => [
        '[&>ul]:space-y-6',
        '[&>ul>li]:bg-gray-50 [&>ul>li>.fi-fo-builder-item-header]:bg-gray-100',
        '[&>ul>li>.fi-fo-builder-item-header]:rounded-t-xl [&>ul>li.fi-collapsed>.fi-fo-builder-item-header]:rounded-b-xl',
        /* The class below ensures that all options of the "content-blocks select list" (when
         | adding a content-block) are always visible. Needed as in some cases, a negative top value
         | resulted in the top option(s) to be outside the viewport */
        '[&_.fi-fo-builder-block-picker_.fi-dropdown-panel]:!top-[unset]',
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
    | If you have created a different name column than the defaults Laravel provides, you can change that here as well.
    */
    'author_model' => 'App\Models\User',
    'author_name_column' => 'name',

    /*
    |--------------------------------------------------------------------------
    | Block specific configuration
    |--------------------------------------------------------------------------
    |
    | All configuration parameters related to blocks can be customised per block class. You can use all configuration
    | parameters used above to customise a block class.
    */
    'block_specific' => [
        CallToActionBlock::class => [
            'call_to_action_number_of_items' => [
                'min' => 1,
                'max' => 3,
            ],
        ],
        CollapsibleGroupBlock::class => [
            'rich_editor' => [
                'toolbar_buttons' => [
                    'bold',
                    'italic',
                    'link',
                    'bulletList',
                    'orderedList',
                ],
            ],
        ],
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
        VideoBlock::class => [
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

    /*
    |--------------------------------------------------------------------------
    | Block preview
    |--------------------------------------------------------------------------
    |
    | If you prefer to render read-only previews in the content block builder instead of the blocks’ forms, you can
    | enable this.
    |
    | You can set whether the previews are interactive or not.
    |
    | The preview blocks will probably need the CSS stylesheet of the website in which the content is rendered. In case,
    | you have set up this stylesheet differently from the default Laravel app.css, you can change this also.
    */
    'block_preview' => [
        'enabled' => false,
        'previews_are_interactive' => false,
        'stylesheet' => 'resources/css/app.css',
    ],
];
