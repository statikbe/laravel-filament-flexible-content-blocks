---
name: filament-flexible-content-blocks-custom-block
description: Create or modify a custom content block for the statikbe/laravel-filament-flexible-content-blocks package - the block class, its Filament form schema, its Blade view, media collections, translations and registration on a model. Use when adding a new block type to the Filament content blocks builder, or when a custom block does not show up in the builder, shows raw translation keys, or fails to render on the front-end.
---

# Creating a custom flexible content block

## When to use this skill

Use this skill when the project uses `statikbe/laravel-filament-flexible-content-blocks` and you need a
block type that the package does not ship (an FAQ accordion, a statistics band, a form embed, ...), or
when you are debugging a custom block.

If instead you are wiring the package onto a model for the first time (migration, model traits, Filament
resource, front-end view), use the `filament-flexible-content-blocks-setup` skill.

This works identically when the project uses
[statikbe/laravel-filament-flexible-content-block-pages](https://github.com/statikbe/laravel-filament-flexible-content-block-pages),
the ready-made CMS built on top of this package: custom blocks are written the same way and registered
on that package's page model.

## The rule that breaks everything if you get it wrong

Custom blocks extend **`Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock`**.

```php
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class FaqBlock extends AbstractContentBlock { /* ... */ }
```

**Do NOT extend `AbstractFilamentFlexibleContentBlock`.** That subclass exists only for blocks that live
*inside* the package. It hard-codes the package's own namespaces:

| It implements for you | ...which for your block means |
| --- | --- |
| `getName()` = `'filament-flexible-content-blocks::' . getNameSuffix()` | Your block is named as if it were a package block, and its media collection and stored `type` key sit in the package namespace. |
| `getLabel()` / `getFieldLabel()` | Labels are looked up in `filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.*`, so your block shows the raw translation key in the builder. |
| `render()` | Renders `filament-flexible-content-blocks::content-blocks.{theme}{suffix}`, a view inside the package that does not exist for your block - `View not found`. |

The class doc-block says so too: *"Blocks implemented outside of the filament-flexible-content-blocks
package should extend AbstractContentBlock!"*

`AbstractContentBlock` extends `Illuminate\View\Component`, so your block is a Blade component; it wires
itself into the Filament builder through `make()`, which you inherit.

## Workflow

1. **Create the class** in `app/ContentBlocks/{Name}Block.php`. Copy `templates/CustomBlock.php.stub`
   as a starting point.
2. **Implement the seven required members** (see `reference/block-api.md` for the full signatures):
   - `public static function getName(): string` - a stable, unique, lowercase identifier, e.g. `'faq'`.
   - `public static function getIcon(): \BackedEnum|string` - e.g. `Heroicon::QuestionMarkCircle`.
   - `public static function getLabel(): string` - the label in the block picker.
   - `public static function getFieldLabel(string $field): string` - label per form field.
   - `protected static function makeFilamentSchema(): array|Closure` - the Filament form components.
   - `public function render()` - return the Blade view for the front-end.
   - `public function getSearchableContent(): array` - the block's plain text, for searching.
   Also write a `__construct(Model&HasContentBlocks&HasMedia $record, ?array $blockData)` that calls
   `parent::__construct()` and maps `$blockData` onto public properties.
3. **Create the Blade view** at `resources/views/content-blocks/{name}.blade.php`. Start from
   `templates/custom-block.blade.php.stub`. Public properties and public methods of the block class are
   available in the view (methods as closures: `$replaceParameters($content)`).
4. **Add translations** for the label and the field labels in your own lang files - never in the
   package's namespace.
5. **Register the block on the model** so it appears in the builder and so its media collections are
   registered. See "Registering" below.
6. **Add media handling** only if the block has images: override `addMediaCollectionAndConversion()` and
   use `BlockSpatieMediaLibraryFileUpload`. See `reference/block-api.md`.
7. **Reuse the package's block features** where they fit - block styles, background colours, image
   position/width/conversion. See `reference/concerns.md`.
8. **Make Tailwind see the view**: the front-end CSS must `@source` your
   `resources/views/content-blocks` directory (usually already covered by the app's own view glob).
9. **Verify**: open the Filament resource, add the block, save, and load the front-end page. Run
   `vendor/bin/pint` and the project's static analysis.

## Registering the block

The builder gets its blocks from the model's `registerContentBlocks()`.

- Model uses `HasContentBlocksTrait`: add the class to that model's `registerContentBlocks()`.
  ```php
  public static function registerContentBlocks(): array
  {
      return [
          ...FilamentFlexibleBlocksConfig::getDefaultFlexibleBlocks(),
          FaqBlock::class,
      ];
  }
  ```
- Model uses `HasDefaultContentBlocksTrait`: either add the class to
  `config('filament-flexible-content-blocks.default_flexible_blocks')` (it then applies to every model
  using that trait), or override `registerContentBlocks()` on the model - a method on the class wins
  over the trait's.

Registration order is the order shown in the block picker.

## Checklist

- [ ] Extends `AbstractContentBlock`, **not** `AbstractFilamentFlexibleContentBlock`.
- [ ] `getName()` returns a unique, stable string - it is persisted as the `type` key inside the
      `content_blocks` JSON and used as the media collection name. **Renaming it orphans all existing
      content and images of that block.**
- [ ] Constructor calls `parent::__construct($record, $blockData)` **first** (it generates the block id
      that media lookups depend on).
- [ ] Every value read in the Blade view is a public property or public method on the block class.
- [ ] `getSearchableContent()` returns the block's text, so `getSearchableBlockContent()` stays complete.
- [ ] The block is registered on every model that should offer it.
- [ ] If the block uploads images, `addMediaCollectionAndConversion()` is implemented; otherwise images
      are never converted.

## Common mistakes

- **Raw translation keys in the builder, or `View not found` on render** - the block extends
  `AbstractFilamentFlexibleContentBlock`. Switch to `AbstractContentBlock` and implement `getLabel()`,
  `getFieldLabel()` and `render()` yourself.
- **Block missing from the picker** - not in the model's `registerContentBlocks()`, or `visible()`
  returns `false`.
- **Images upload but never appear** - `addMediaCollectionAndConversion()` was not implemented, the
  upload field's `->collection()` does not match `static::getName()`, or a plain
  `SpatieMediaLibraryFileUpload` was used instead of `BlockSpatieMediaLibraryFileUpload` (which tags the
  media with the block id so multiple instances of the same block keep their own images).
- **All instances of the block show the same image** - same cause: the upload field must be
  `BlockSpatieMediaLibraryFileUpload`.
- **Block styles have no effect** - `render()` must append `getBlockStyleTemplateSuffix()` to the view
  name, and a matching view per style must exist.

## Reference

- `reference/block-api.md` - every method of `AbstractContentBlock`: required, overridable, and helpers.
- `reference/concerns.md` - the reusable traits and block form fields (block style, background colour,
  image position/width/conversion, call-to-action) and their config keys.
- `templates/CustomBlock.php.stub` - a complete block class with an image, block style and background
  colour.
- `templates/custom-block.blade.php.stub` - the matching front-end view.
