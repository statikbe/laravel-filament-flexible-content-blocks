<?php

// config for Statikbe/FilamentFlexibleContentBlocks
use Spatie\Image\Manipulations;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\ImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\OverviewBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\QuoteBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\VideoBlock;
use Statikbe\FilamentFlexibleContentBlocks\Models\Page;

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
    ],

    //not implemented yet!
    'image_conversions' => [
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
                    'width' => 600,
                    'height' => 600,
                    'responsive' => true,
                ],
            ],
        ],
        Page::class => [
            'overview_image' => [
                'thumb' => [
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 400,
                    'height' => 400,
                    'responsive' => true,
                ],
            ],
        ],
    ],

    'overview_models' => [
        //TODO remove & document
        'App\Models\FlexiblePage',
        'App\Models\TranslatableFlexiblePage',
    ],

    'call_to_action_models' => [
        'App\Models\FlexiblePage',
        'App\Models\TranslatableFlexiblePage',
    ],

    'image_position' => [
        'options' => [
            'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
            'center' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.center',
            'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
        ],
        'default' => 'left',
    ],

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
        'default' => 'full',
    ],

    'call_to_action_buttons' => [
        'options' => [
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary',
                'class' => 'btn-primary',
            ],
            'primary_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.primary_chevron',
                'class' => 'btn-primary btn-chevron',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary',
                'class' => 'btn-secondary',
            ],
            'secondary_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.secondary_chevron',
                'class' => 'btn-secondary btn-chevron',
            ],
            'ghost' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost',
                'class' => 'btn-ghost',
            ],
            'ghost_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.ghost_chevron',
                'class' => 'btn-ghost btn-chevron',
            ],
            'link' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link',
                'class' => 'btn-link',
            ],
            'link_chevron' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.call_to_action_btn.link_chevron',
                'class' => 'btn-link btn-chevron',
            ],
        ],
        'default' => 'full',
    ],

    'background_colours' => [
        'options' => [
            'default' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.default',
                'class' => '',
            ],
            'primary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.primary',
                'class' => 'bg-primary-400',
            ],
            'secondary' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.secondary',
                'class' => 'bg-secondary-400',
            ],
            'grey' => [
                'label' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.background_colour.grey',
                'class' => 'bg-gray-400',
            ],
        ],
        'default' => 'default',
    ],

    'grid_columns' => [
        1, 2, 3, 4,
    ],

    'formatting' => [
        'publishing_dates' => 'd/m/Y G:i',
    ],

    'author_model' => 'App\Models\User',
    //TODO configurable author search fields

    'block_specific' => [
        TextImageBlock::class => [
            'image_position' => [
                'options' => [
                    'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
                    'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
                ],
                'default' => 'left',
            ],
        ],
    ],

];
