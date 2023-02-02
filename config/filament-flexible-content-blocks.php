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
                    //TODO kijken naar medialibrary config:
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 1200,
                    'height' => 630,
                ],
            ],
            'overview_image' => [
                'overview_image' => [
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 600,
                    'height' => 600,
                ],
            ],
        ],
        Page::class => [
            'overview_image' => [
                'thumb' => [
                    'fit' => Manipulations::FIT_CROP,
                    'width' => 400,
                    'height' => 400,
                ],
            ],
        ],
    ],

    'formatting' => [
        'publishing_dates' => 'd/m/Y G:i',
    ],
];
