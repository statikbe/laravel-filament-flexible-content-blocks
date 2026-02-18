# Changelog

All notable changes to `laravel-filament-flexible-content-blocks` will be documented in this file.

## v4.0.1 - 2026-02-18

- Add getters and parameter replacement to title, intro, seo_title, seo_description, overview_title, overview_description, hero_title & hero_copyright
- Fix typing for icons from Heroicon to \BackedEnum to allow any icon set.

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v4.0.0...v4.0.1

## v3.0.1 - 2026-02-18

### What's Changed

* Add getters and parameter replacement to title, intro, seo_title, seo_description, overview_title, overview_description, hero_title & hero_copyright

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v3.0.0...v3.0.1

## v4.0.0 - 2026-02-12

### What's Changed

* Filament v4 compatibility by @sten, @sevbesau, @AurelDemiri, @lukasdewijn  in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/86

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.5...v4.0.0

## v3.0.0 - 2026-02-11

- Add getter for page title, to make this more extendable
- Bump semver version to match Filament versioning

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.5...v3.0.0

## v2.8.5 - 2026-02-11

Add getter for page title, to make this more extendable

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.4...v2.8.5

## v2.8.4 - 2026-02-10

Fix icon on publish action

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.3...v2.8.4

## v2.8.3 - 2026-02-09

Add model property typing to HasSEOAttributes

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.2...v2.8.3

## v2.8.2 - 2026-02-09

- Add seoImage(): MorphMany & heroImage(): MorphMany relationship to interface
- Reduce duplicate queries in overview block
- fix phpstan errors

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.1...v2.8.2

## v2.8.1 - 2026-02-04

* Add CodeColumn and CodeFilter for tables
* Add ViewPageAction for redirecting to the view url of the page via the Linkable interface
* Improve CTA label to adapt it to the chosen type

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.8.0...v2.8.1

## v2.8.0 - 2026-02-03

### What's Changed

* Video background component for hero section, currently only including youtube playback, see PR by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/87
* Improve hero section title

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.7.3...v2.8.0

## v2.7.2 - 2026-01-28

- Handle `responsive` config key when value is false

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.7.1...v2.7.2

## v2.7.1 - 2026-01-22

- Make hero video url field in hero image section optional with create flag

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.7.0...v2.7.1

## v2.7.0 - 2026-01-19

- make hero changes more flexible, see FlexibleSelectField
- video support in hero
- add tests
- fix phpstan errors

## v2.6.4 - 2025-10-21

- Improved config return type for getCallToActionModels

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.6.3...v2.6.4

## v2.6.3 - 2025-10-16

### What's Changed

* Improved typing for `isParentOf` function on Page model

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.6.2...v2.6.3

## v2.6.2 - 2025-10-08

### What's Changed

* Update ContentBlocks.php by @Sindoweb in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/78

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.6.1...v2.6.2

## v2.6.1 - 2025-10-01

New content block: Collapsible group.
This is a block with collapsible items with a title and description. This is useful for example for FAQ pages.

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/2.6.0...v2.6.1

## v2.6.0 - 2025-09-24

This is a release with many new tweaks and features for [the new Flexible Content Block Pages package](https://github.com/statikbe/laravel-filament-flexible-content-block-pages).

### What's Changed

* Quote-block optional image by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/70
* Allow call to actions on a page for the hero component by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/72
* Possibility to overrule, via a setting, the min- and max allowed cta's within both Cards and TextImage content blocks by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/71
* ensuring all options selectable in the "content-blocks select list by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/75
* French copy - Update filament-flexible-content-blocks.php by @sigridviaene in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/76
* use intro as fallback for overview description
* hide copy content blocks to other locales action, if there is only 1 locale
* fix block preview label: strip html tags
* convenience function `getByCode` to query model by code
* smarter language switch action that hides the lang switch when there is only 1 locale
* deprecate the TextBlock because it is very similar to other blocks
* improve replicate action: add "(copy)" to title
* fix builder block popup to not fall off the window
* many small bugs

### New Contributors

* @sigridviaene made their first contribution in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/76

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.5.5...2.6.0

## v2.5.5 - 2025-07-02

Fix bug in call to action data validation

## v2.5.4 - 2025-07-02

Fix bug in call to actions

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.5.3...v2.5.4

## v2.5.3 - 2025-06-26

- Revert `getLabel()` back to make sure it the package stays backward compatible with existing project with custom blocks.
- Introduced a new function `getContextualLabel()` which can be overwritten in custom blocks to provide more block context in the label

## v2.5.2 - 2025-06-18

### What's Changed

* Fix image position overrule by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/69

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v.2.5.1...v2.5.2

## v2.5.1 - 2025-06-17

### What's Changed

* [flex content blocks] fix so that one can overrule the 'call_to_actio… by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/68

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.5.0...v.2.5.1

## v2.5.0 - 2025-06-13

### What's Changed

* Improved loading of CSS-styles for the block previews and updated docs on how to support different fonts in preview blocks.
* Flex content blocks styling improvements for better UX while editing by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/64
* Flex content blocks labels based on content by @bverbist in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/66

### New Contributors

* @bverbist made their first contribution in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/64

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.4.0...v2.5.0

## v2.4.0 - 2025-06-03

- Replicate actions for table and form to copy a model with content blocks and copy all images
- Fix deprecation warnings
- Fix (some) typing phpstan errors

## v2.3.0 - 2025-05-09

New feature:

- block previews!

**Note:** update your configuration file with the new configuration settings for block previews (see at the bottom of the config file).

## v2.2.1 - 2025-03-18

### What's Changed

* Update ParentField.php by @Sindoweb in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/50

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.2.0...v2.2.1

## v2.2.0 - 2025-03-04

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/60
* Laravel 12 Compatibility by @laraben in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/63

### New Contributors

* @laraben made their first contribution in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/63

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.1.8...v2.2.0

## v2.1.8 - 2025-01-30

Improve translatable field hint: do not show hint when only one locale is set.

## v2.1.7 - 2024-12-11

### What's Changed

* Fix scope `published` to return correct published posts by @andrii-trush in https://github.com/statikbe/laravel-filament-flexible-content-blocks/pull/56

**Full Changelog**: https://github.com/statikbe/laravel-filament-flexible-content-blocks/compare/v2.1.6...v2.1.7

## v2.1.6 - 2024-12-02

Fix model entry to found exception which results in a 404 page for assets in CTA fields

## v2.1.5 - 2024-10-10

Fix copy blocks to all locales actions

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
