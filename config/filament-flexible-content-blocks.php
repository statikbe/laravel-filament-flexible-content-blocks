<?php

// config for Statikbe/FilamentFlexibleContentBlocks
use Spatie\Image\Manipulations;
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

    ],

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

    'image_position' => [
        'options' => [
            'left' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.left',
            'center' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.center',
            'right' => 'filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.image_position.right',
        ],
        'default' => 'left',
    ],

    'formatting' => [
        'publishing_dates' => 'd/m/Y G:i',
    ],

    'author_model' => 'App\Models\User',
];
