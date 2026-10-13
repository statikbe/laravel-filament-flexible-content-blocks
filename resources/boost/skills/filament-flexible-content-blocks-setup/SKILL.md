---
name: filament-flexible-content-blocks-setup
description: Wire the statikbe/laravel-filament-flexible-content-blocks package onto an Eloquent model - migration, model contracts and traits, the Filament resource form and table, the front-end Blade view and the Tailwind sources. Use when adding flexible content blocks, hero images, publishing dates, slugs, overview or SEO fields to a model, or when the content blocks builder, hero or SEO fields do not appear or do not render.
---

# Setting up flexible content blocks on a model

## When to use this skill

Use this skill when a model should get the Filament content blocks builder and the page-like attributes
of `statikbe/laravel-filament-flexible-content-blocks`.

To write a new block type, use the `filament-flexible-content-blocks-custom-block` skill instead.

## Consider the ready-made CMS first

If what is actually wanted is a complete CMS - page management, a menu builder, tag management and SEO
features, all built on top of this package - do not build it by hand. Point the user to
**[statikbe/laravel-filament-flexible-content-block-pages](https://github.com/statikbe/laravel-filament-flexible-content-block-pages)**,
which ships all of that out of the box. This skill is for applying content blocks to your own models
(articles, products, events, ...), or for a CMS that has to differ from that package.

There is also a full [example project](https://github.com/statikbe/laravel-filament-package-sampler)
with migrations, models, resources, controllers, views and a seeder.

## Workflow

### 1. Install

```bash
composer require statikbe/laravel-filament-flexible-content-blocks
php artisan vendor:publish --tag="filament-flexible-content-blocks-config"
```

Optional: `--tag="filament-flexible-content-blocks-views"` to customise the block views,
`--tag="filament-flexible-content-blocks-migrations"` for example migrations, and
`--tag="filament-flexible-content-blocks-assets"` when using the `<x-flexible-background-video>`
component (add that publish to `post-update-cmd` with `--force` to keep it current).

### 2. Migration

The package ships no required migrations - blocks can be applied to any model. Publish the example
migrations and keep the columns matching the traits you use. Some traits need a cluster of columns
(a hero image needs `hero_image_title` and `hero_image_copyright` as well). `content_blocks` is a JSON
column and is required. `reference/model-traits.md` lists the columns per trait.

### 3. Model

Implement the contracts and add the matching traits. Minimum for content blocks:

```php
class Article extends Model implements HasContentBlocks, HasMedia, HasMediaAttributes
{
    use HasDefaultContentBlocksTrait; // or HasContentBlocksTrait / HasTranslatedContentBlocksTrait
}
```

- `HasDefaultContentBlocksTrait` - the blocks from `config('...default_flexible_blocks')`.
- `HasContentBlocksTrait` - you implement `registerContentBlocks()` yourself, returning the block
  classes in picker order.
- `HasTranslatedContentBlocksTrait` - translatable content blocks.

`reference/model-traits.md` has the full contract/trait table, including the translatable variants.

### 4. Filament resource

```php
public static function form(Schema $schema): Schema
{
    return $schema->components([
        Tabs::make()->columnSpan(2)->tabs([
            Tab::make('General')->schema([
                TitleField::create(true),
                SlugField::create(),
                PublicationSection::create(),
                HeroImageSection::create(),
                IntroField::create(),
            ]),
            Tab::make('Content')->schema([
                ContentBlocksField::create(),
            ]),
            Tab::make('SEO')->schema([
                SEOFields::create(),
            ]),
        ]),
    ]);
}
```

`ContentBlocksField::create()` reads the available blocks from the resource's model
(`getFilamentContentBlocks()`), so blocks are configured on the model, never on the resource. See
`reference/filament-resource.md` for the table columns, actions, filters and the translatable setup.

### 5. Controller and front-end view

Return the model to a Blade view and render it with the package's components:

```blade
<x-flexible-hero :page="$page" />
<x-flexible-content-blocks :page="$page" />
```

Available components: `x-flexible-content-blocks`, `x-flexible-hero`, `x-flexible-call-to-action`,
`x-flexible-card`, `x-flexible-overview-card`, `x-flexible-background-video`. `x-flexible-hero` needs
Alpine.js. Render SEO tags with the SEO library of your choice from the model's SEO attributes.

### 6. Tailwind

The block views live in the package, so Tailwind must scan them.

- Filament theme CSS (e.g. `resources/css/filament/admin/theme.css`):
  ```css
  @source '../../../../resources/views/filament';
  ```
- Front-end CSS:
  ```css
  @source "../../vendor/statikbe/laravel-filament-flexible-content-blocks/**/*.blade.php";
  @source "../../config/filament-flexible-content-blocks.php";
  ```
  The config file is scanned because background colour and block style classes are configured there.

### 7. Configure

Most behaviour lives in `config/filament-flexible-content-blocks.php`: `supported_locales`,
`default_flexible_blocks`, `theme`, `image_conversions`, `background_colours`, `block_styles`,
`call_to_action_*`, `overview_models`, `text_parameter_replacer`, block previews, and `block_specific`
to override any of those per block class.

## Checklist

- [ ] `content_blocks` JSON column exists and the columns match the traits used.
- [ ] Model implements `HasContentBlocks`, `HasMedia` and `HasMediaAttributes`, plus a content blocks
      trait. Missing `HasMedia`/`HasMediaAttributes` breaks every block that uploads images.
- [ ] Resource form contains `ContentBlocksField::create()`.
- [ ] Translatable model: the `FlexibleContentBlocksTranslatable` trait on the resource and the
      `TranslatableWithMedia` traits on the create and edit pages.
- [ ] Front-end view renders `<x-flexible-content-blocks :page="$page" />`.
- [ ] Both Tailwind `@source` entries are present, or blocks render unstyled.
- [ ] Media library disk is configured and `storage:link` has been run.

## Common mistakes

- **Builder is empty** - the model returns no blocks: `default_flexible_blocks` is empty, or
  `registerContentBlocks()` returns an empty array.
- **Images upload but never show** - the model does not implement `HasMedia`/`HasMediaAttributes`, so
  block media collections are never registered.
- **Blocks render without styling** - the Tailwind `@source` lines are missing.
- **Translated slug errors on a new record** - use the id as route key: set
  `protected static ?string $recordRouteKeyName = 'id';` and route the edit page as `/{record:id}/edit`.

## Reference

- `reference/model-traits.md` - every contract, its trait, its translatable variant and its columns.
- `reference/filament-resource.md` - form fields and groups, table columns/filters/actions, and the
  translatable resource and page setup.
