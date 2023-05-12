# Changelog

All notable changes to `laravel-filament-flexible-content-blocks` will be documented in this file.

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
