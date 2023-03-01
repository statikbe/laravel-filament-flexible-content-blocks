# Laravel Filament Flexible Content Blocks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-filament-flexible-content-blocks.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-content-blocks)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-content-blocks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-content-blocks/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-content-blocks/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-content-blocks/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-filament-flexible-content-blocks.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-content-blocks)

The Laravel Filament Flexible Content Blocks package helps you with easily creating content in Filament for any 
model, with predefined blocks, and foreach block an extendable Blade view component. 

You can use this opinionated package to create a basic CMS, by setting up your own model and implementing the predefined traits to 
select the functionality you need, and quickly setup a Filament resource by implementing the ready-made fields. Or you can 
add flexible content to a model for your specific business case, for instance to allow the flexible creation of a 
product description. Each project is different and to foster changing requirements, the focus is on the building blocks and 
there is no default implementation for a CMS-like page.

The key goals of this package are:
- provide a quick way to add content to a model through reusable fields and content blocks
- quickly set up the frontend
- have fully-working, extendable blade view components with basic Tailwind styling 
- allow the liberty to pick and choose which fields and blocks you need for your requirements
- provide easy configuration to override the behaviour of the fields, blocks and image conversions

## Installation

You can install the package via composer:

```bash
composer require statikbe/laravel-filament-flexible-content-blocks
```

You will most likely need to edit the configuration, so you can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-config"
```

There are [many configuration options](#configuration).

Optionally, you can publish the views (e.g. if you want to tweak the content blocks) using:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-views"
```

Since you can apply the flexible content blocks to any view, we do not provide required migrations. 
However, we provide two example migrations, one for a translatable page and one for a single-language page. 
You can use these migrations as an example to create your own migrations. (see [ToDo's](#todo))
You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-migrations"
php artisan migrate
```

## Usage

There is [an example implementation](./example) of all the package features, which includes:
- migrations
- data models
- Filament resources & pages
- Http controllers
- views



## Configuration



## Blocks


## Todo

Below is a list of ideas and missing features. PR's are welcome!

- Command to generate migrations
- Command to generate models
- Command to generate Filament resource and pages
- Integrate existing image asset manager
- Allow custom, undefined image conversions in configuration
- Store links to models in rich editor
- Redirects
- Reusable blocks. Name: global block?

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please, submit bugs or feature requests via the [Github issues](https://github.com/statikbe/laravel-filament-chained-translation-manager/issues).
Pull requests are welcomed! Thanks!

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sten Govaerts](https://github.com/sten)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
