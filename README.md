<p align="center"><img src="documentation/img/banner-laravel-filament-flexible-content-blocks.png" alt="Laravel Filament Flexible cContent Blocks"></p>

# Laravel Filament Flexible Content Blocks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/statikbe/laravel-filament-flexible-content-blocks.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-content-blocks)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-content-blocks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-content-blocks/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/statikbe/laravel-filament-flexible-content-blocks/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/statikbe/laravel-filament-flexible-content-blocks/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/statikbe/laravel-filament-flexible-content-blocks.svg?style=flat-square)](https://packagist.org/packages/statikbe/laravel-filament-flexible-content-blocks)

The Laravel Filament Flexible Content Blocks package helps you to easily create content in Filament for any 
model, with predefined blocks, and foreach block an extendable Blade view component. 

You can use this opinionated package to create a basic CMS, by setting up your own page model and implementing the predefined traits to 
select the functionality you need, then quickly setup a Filament resource by implementing the ready-made fields. Or you can 
add flexible content to a model for your specific business case, for instance to allow the flexible creation of a 
product description. Each project is different and to foster changing requirements, the focus is on the building blocks and 
not a default implementation for a CMS-like page.

The key goals of this package are:
- provide a quick way to add content to a model through reusable fields and content blocks
- quickly set up the frontend and allow different frontend stylings for each block
- have fully-working, extendable Blade view components with basic Tailwind styling 
- allow the liberty to pick and choose which fields and blocks you need for your requirements
- provide easy configuration to override the behaviour of the fields, blocks and image conversions
- support SEO
- support overview fields to display the content in a list with custom title, image and description
- support content translations
- provide a start set of content blocks for most general requirements

## Installation

You can install the package via composer:

```bash
composer require statikbe/laravel-filament-flexible-content-blocks
```

You will most likely need to edit [the extensive configuration](#configuration), so you can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-config"
```

Optionally, you can publish the views (e.g. if you want to tweak the content blocks) using:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-views"
```

Since you can apply the flexible content blocks to any view, we do not provide required or default migrations. 
However, we provide two example migrations, one for a translatable page and one for a single-language page. 
You can use these migrations as an example to create your own migrations. (see [ToDo's](#todo))
You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-flexible-content-blocks-migrations"
#first edit the migrations and then run:
php artisan migrate
```

## Dependencies

This is an opinionated package with batteries included. So we picked a set of dependencies to build upon. 
Here is a brief overview of the choices made:

- `filament/filament`: obviously ;-) 
- `spatie/laravel-medialibrary` & `filament/spatie-laravel-media-library-plugin`: all image handling is done with `spatie/medialibrary` 
- `spatie/laravel-sluggable`: for slugs
- `spatie/laravel-translatable`: for translations as this works together with the first party filament translatable package. 

## Usage

There is [an example implementation](./example) of all the package features, which includes:
- migrations
- data models
- Filament resources & pages
- Http controllers
- views

You can use this as a starting point to see how the package can be used for a simple page (there are two models for both regular and translated content). 
Below we briefly discuss how to setup a migration, a model and a Filament resource in four steps. In the future,
we want to add question-based commands to create these, see [roadmap](#roadmap).

### 1. Setup migrations

You can start from [the example migrations that can be published](#installation). Then prune and pick the fields that fit
your requirements, note that some model traits need a combination of fields, e.g. the hero image needs both `hero_image_copyright` and
`hero_image_title`. The clusters are commented in the migration or have a look in [the model traits](src/Models/Concerns) to get an idea.

### 2. Setup the model

If you do not have a model yet, create one with `php artisan make:model`, then you can add the interfaces and its default
implementation via traits. Below is an overview of the provided interfaces and traits and their functionality:

#### __[HasPageAttributes](src%2FModels%2FContracts%2FHasPageAttributes.php)__:
This adds a title and publishing begin and end date variables, together with functions and scopes to help with
finding published models. Implement this interface with the trait [HasPageAttributesTrait](src%2FModels%2FConcerns%2FHasPageAttributesTrait.php) 
or [HasTranslatedPageAttributesTrait](src%2FModels%2FConcerns%2FHasTranslatedPageAttributesTrait.php). 
for translated content.

#### __[HasIntroAttribute](src%2FModels%2FContracts%2FHasIntroAttribute.php)__:
This adds an introduction text variable. Implement this interface with the trait [HasIntroAttributeTrait](src%2FModels%2FConcerns%2FHasIntroAttributeTrait.php) 
or [HasTranslatedIntroAttributeTrait](src%2FModels%2FConcerns%2FHasTranslatedIntroAttributeTrait.php).

#### __[HasCode](src%2FModels%2FContracts%2FHasCode.php)__:
Adds a code variable to be able to select a specific content model in your source code by string instead of a 
varying id or slug. For instance, this is useful to look up a home page. Implement this with the trait [HasCodeTrait](src%2FModels%2FConcerns%2FHasCodeTrait.php).

#### __[HasHeroImageAttributes](src%2FModels%2FContracts%2FHasHeroImageAttributes.php)__:


#### __[HasContentBlocks](src%2FModels%2FContracts%2FHasContentBlocks.php)__:


#### __[HasMediaAttributes](src%2FModels%2FContracts%2FHasMediaAttributes.php)__:
Always include this interface if you use any image functionality. It provides some helper functions. 
You do not need to add traits, since the trait will be included by other traits that handle images.

#### __[HasOverviewAttributes](src%2FModels%2FContracts%2FHasOverviewAttributes.php)__:
Overview fields can be used to display the content models as brief snippets in lists, for instance a list of news articles.
Implement 

#### __[HasSEOAttributes](src%2FModels%2FContracts%2FHasSEOAttributes.php)__:
This adds a new title, description, image and keywords for SEO. It provides fallbacks to the regular title, intro 
and hero image if no SEO fields are completed. Implement the 
[HasSEOAttributesTrait](src%2FModels%2FConcerns%2FHasSEOAttributesTrait.php) or the
[HasTranslatedSEOAttributesTrait](src%2FModels%2FConcerns%2FHasTranslatedSEOAttributesTrait.php) for translatable content.   

#### __[Linkable](src%2FModels%2FContracts%2FLinkable.php)__:


### 3. Setup the Filament resource

### 4. Setup the controller and Blade view

## Configuration

Documentation is WIP.

## Blocks

Documentation is WIP.

## Roadmap

Below is a list of ideas and missing features. PR's are welcome!

- Command to generate migrations
- Command to generate models
- Command to generate Filament resource and pages
- Integrate image asset manager
- Store links to models in rich editor
- Redirects
- Reusable blocks. Name: global block?
- Focal point for image resizing

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please, submit bugs or feature requests via the [Github issues](https://github.com/statikbe/laravel-filament-chained-translation-manager/issues).
Pull requests are welcomed! Thanks!

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sten Govaerts](https://github.com/sten)
- [Stijn Elskens](https://github.com/stijnelskens)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
