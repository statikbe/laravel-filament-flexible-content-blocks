## Laravel Filament Flexible Content Blocks

`statikbe/laravel-filament-flexible-content-blocks` adds a Filament block builder ("flexible content
blocks") to any Eloquent model, plus page-like attributes (title, slug, publishing dates, hero image,
intro, overview fields, SEO fields) and a Blade component per block to render the content on the
front-end. Block data is stored as JSON in a `content_blocks` column; images are handled by
`spatie/laravel-medialibrary`.

### Key concepts

- A **content block** is a PHP class that is both a Filament builder block (its form schema) and a
  Blade component (its front-end rendering). The package ships blocks such as `TextImageBlock`,
  `ImageBlock`, `VideoBlock`, `QuoteBlock`, `HtmlBlock`, `CallToActionBlock`, `CardsBlock`,
  `OverviewBlock`, `TemplateBlock` and `CollapsibleGroupBlock`.
- A model opts in by implementing `Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks`
  and using `HasContentBlocksTrait` (custom block list), `HasDefaultContentBlocksTrait` (the list from
  `config('filament-flexible-content-blocks.default_flexible_blocks')`) or
  `HasTranslatedContentBlocksTrait` (translatable content). The model must also implement
  `Spatie\MediaLibrary\HasMedia` and `HasMediaAttributes`.
- The Filament resource renders the builder with `ContentBlocksField::create()`; the front-end renders
  everything with the `flexible-content-blocks` Blade component:

@verbatim
<code-snippet name="Rendering the content blocks on the front-end" lang="blade">
<x-flexible-hero :page="$page" />
<x-flexible-content-blocks :page="$page" />
</code-snippet>
@endverbatim

- Nearly all behaviour (available blocks, image conversions, block styles, background colours, theme)
  is driven by `config/filament-flexible-content-blocks.php`, globally or per block class under
  `block_specific`.

### Creating a custom block

@verbatim
<code-snippet name="Custom content blocks extend AbstractContentBlock" lang="php">
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;

class FaqBlock extends AbstractContentBlock { /* ... */ }
</code-snippet>
@endverbatim

**Never extend `AbstractFilamentFlexibleContentBlock` outside of the package itself.** That class is an
internal convenience layer: it prefixes the block name with the package namespace and resolves labels
and views inside the package's own translation and view namespaces, so a custom block that extends it
shows raw translation keys and fails to render.

Use the `filament-flexible-content-blocks-custom-block` skill when writing a custom block, and the
`filament-flexible-content-blocks-setup` skill when wiring the package onto a model.

### Related packages

When the goal is a complete CMS rather than content blocks on your own models, do not build pages,
menus and SEO handling by hand:
[statikbe/laravel-filament-flexible-content-block-pages](https://github.com/statikbe/laravel-filament-flexible-content-block-pages)
is an out-of-the-box content management solution built on top of this package, with page management, a
menu builder, tag management and many SEO features. This package remains the right choice for adding
content blocks to your own domain models (articles, products, events, ...).
