# Changelog

All notable changes to `laravel-filament-flexible-content-blocks` will be documented in this file.

## v2.1.4 - 2024-10-09

Enforce media library v11 because we use the enum Fit in the config file.

## v2.1.3 - 2024-09-30

### What's Changed

* Update VideoBlock.php by @Sindoweb in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/46

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.1.1...v2.1.3

## v2.1.1 - 2024-09-30

Fix small bug in hasImage function of blocks.

## v2.1.0 - 2024-09-27

This release contains a refactor of the spatie medialibrary block image field. The field no longer stores a Media UUID in the content blocks data but makes now fully use of the block ID to filter out the correct media.

**IMPORTANT:** The refactor required a small change in the Blade view of the Cards Block. In case you would have published this view, please use [the upgrade guide](https://github.com/statikbe/laravel-filament-flexible-content-blocks/blob/main/UPGRADE.md).

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.0.3...v2.1.0

## v2.0.3 - 2024-07-10

- Make call to actions more configurable to support the new asset manager package, see https://github.com/statikbe/laravel-filament-flexible-blocks-asset-manager

## v2.0.1 - 2024-06-07

### What's Changed

* Update parent-child.md by @Sindoweb in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/39
* Small label updates
* EN translation of template block
* Improve default HTML/CSS/JS of blocks

### New Contributors

* @Sindoweb made their first contribution in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/39

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.0.0...v2.0.1

## v2.0.0 - 2024-05-22

- Laravel 11 upgrade.
- The image conversions in the configuration file have changed, please read the UPGRADE.md file for details. Since this is a breaking change, the major version has increased.

## v1.0.7 - 2024-05-03

- Fix urgent bug in SpatieMediaLibraryFileUpload when media is null. This fix should be removed once the PR is released.

## v1.0.6 - 2024-04-19

- Fix saving of content blocks: the hydrated data was json encoded, which changed the ordering of blocks and cards.

## v1.0.5 - 2024-04-03

- add uuids to translated content blocks

## v1.0.4 - 2024-04-03

- Convert image uuids to arrays in content blocks when switching the locale

## v1.0.3 - 2024-04-03

- Add block id field to CardData so it is properly persisted.

## v1.0.1 - 2024-04-03

- Handle dirty builder blocks. Sometimes the builder blocks are saved with image id as arrays, instead of a UUID. This fix makes the code typing more resistant to this.

## v1.0.0 - 2024-03-28

**IMPORTANT:** If you are upgrading from an older version, the content blocks data structure needs to be migrated. Please read the [upgrade guide](https://github.com/statikbe/laravel-filament-flexible-content-blocks/blob/main/UPGRADE.md).

- images in the content blocks are now more thightly linked to the block
- translatable and block spatie image fields are now making use of the refactored filament base classes
- fixed bug in translatable spatie image field that deleted other images of other media collections.
- AI button the generate SEO fields with OpenAI (see docs for setup)
- several bugs have been handled

No Laravel 11 support yet :-(. The next step is to refactor to the latest spatie-image lib to be able to support Laravel 11.

## v0.2.12 - 2024-01-24

- fixes bug in block image field.

## v0.2.11 - 2024-01-21

- fix bug with translatable image field due to update in filament lib
- fix content blocks copy action to update form data after copy

## v0.2.10 - 2023-12-14

Fix bug in saving models with translatable media.

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v0.2.9...v0.2.10

## v0.2.9 - 2023-12-08

- fixes bug in slug field with non translatable models

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v0.2.8...v0.2.9

## v0.2.8 - 2023-11-17

- Fix in block image field to avoid deletion of images in other blocks of the same type.

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v0.2.7...v0.2.8

## v0.2.7 - 2023-11-09

Added support for the image editor of Filament. Fully configurable via config file.

## v0.2.6 - 2023-11-07

- Support for parent - child relationships between content models. This is useful to for example create nested pages or nested slugs for URLs.

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v0.2.5...v0.2.6

## v0.2.5 - 2023-11-05

### What's Changed

- Fix: HasSlugAttributeTrait referenced `$this` when in a static context by @HelgeSverre in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/22
- Fix: Initialize properties to null in Hero component class by @HelgeSverre in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/23
- Mention the Table action "ViewAction" in the README. by @HelgeSverre in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/24

### New Contributors

- @HelgeSverre made their first contribution in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/22

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v0.2.4...v0.2.5

## v0.2.4 - 2023-10-20

- fix bug with spatie image fields in content blocks, where a newly uploaded image gets deleted immediately after a new image is uploaded.

## v0.2.3 - 2023-10-17

Fix bug with extra newly defined image conversions.

## v0.2.2 - 2023-10-16

- fix CTA block creation bug

## v0.2.1 - 2023-08-29

Fix bug in block image upload field which caused images to be duplicated and deleted.

## v0.2.0 - 2023-08-01

Completed configuration documentation

## v0.1.13 - 2023-07-31

- Fix bug with SEO image fallback to hero image

## v0.1.12 - 2023-07-31

- Let SEO and overview images fallback to Hero image media and urls when not set.

## v0.1.10 - 2023-07-03

- add docs
- make required text field in CTA block not required
- fix bugs in hero & overview image url getter
- fix slug
- fix styling

## v0.1.9 - 2023-05-15

Cascade attributes bag of content blocks component to the blocks

## v0.1.8 - 2023-05-15

Fix bug in translatable spatie medialibrary image upload field, that would delete all translated media.

## v0.1.7 - 2023-05-12

- Table filter to filter published/unpublished models: Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters\PublishedFilter
- unpublished scope
- sortable title table column

## v0.1.6 - 2023-05-11

- slug changed event: send when a slug of a model has changed if the model is published (if this trait is implemented) and already had a slug before. This can be useful to automate redirect rules on slug changes.
- clean up of config file.
- more docs

## v0.1.5 - 2023-05-05

Support for text replacement parameters: you can add :param sentinels in the text fields of content blocks and they will be replaced by data.

## v0.1.4 - 2023-05-04

make searchable text content from content blocks cleaner when HTML is stripped, HTML entities en special chars are removed

## v0.1.3 - 2023-05-04

adds support to extract searchable text content from content blocks to be able to easily index this data in a seach index or database column for text search

## v0.1.2 - 2023-04-28

- fix bug: allow null image ids in blocks

## v0.1.1 - 2023-04-26

- Fix bug in published scope
- Add documentation

## v0.1.0 - 2023-04-25

Initial release
