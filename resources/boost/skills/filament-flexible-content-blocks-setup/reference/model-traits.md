# Model contracts, traits and columns

Contracts live in `Statikbe\FilamentFlexibleContentBlocks\Models\Contracts`, traits in
`Statikbe\FilamentFlexibleContentBlocks\Models\Concerns`. Add only the clusters you need - each is
independent apart from the media requirement below.

The model must also implement `Spatie\MediaLibrary\HasMedia` and `HasMediaAttributes` as soon as
anything uploads images (hero image, SEO image, overview image, or any block with an image). The hero,
overview and SEO traits already pull in `HasMediaAttributesTrait` and `InteractsWithMedia`; a model that
uses only `HasContentBlocksTrait` has to add both itself.

| Contract | Trait | Translatable trait | Columns | What it adds |
| --- | --- | --- | --- | --- |
| `HasContentBlocks` | `HasContentBlocksTrait` or `HasDefaultContentBlocksTrait` | `HasTranslatedContentBlocksTrait` | `content_blocks` (json) | The flexible content blocks builder and `getSearchableBlockContent()`. **Required for content blocks.** |
| `HasPageAttributes` | `HasPageAttributesTrait` | `HasTranslatedPageAttributesTrait` | `title`, `publishing_begins_at`, `publishing_ends_at` | Title and publishing window, with query scopes for published records. |
| `HasIntroAttribute` | `HasIntroAttributeTrait` | `HasTranslatedIntroAttributeTrait` | `intro` (text) | Introduction text. |
| `HasCode` | `HasCodeTrait` | - | `code` | A stable string key to look a record up in code (`Model::getByCode('home')`). |
| `HasHeroImageAttributes` | `HasHeroImageAttributesTrait` | `HasTranslatedHeroImageAttributesTrait` | `hero_image_title`, `hero_image_copyright` | Hero image (media library) with alt text and copyright. |
| `HasHeroVideoAttribute` | `HasHeroVideoAttributeTrait` | `HasTranslatedHeroVideoUrlAttributeTrait` | `hero_video_url` | Video playing behind the hero. |
| `HasOverviewAttributes` | `HasOverviewAttributesTrait` | `HasTranslatedOverviewAttributesTrait` | `overview_title`, `overview_description` | Title/description/image used when the record is listed as a card. |
| `HasSEOAttributes` | `HasSEOAttributesTrait` | `HasTranslatedSEOAttributesTrait` | `seo_title`, `seo_description`, `seo_keywords` (json) | SEO title, description, keywords and image, falling back to title, intro and hero image. |
| (slug) | `HasSlugAttributeTrait` | `HasTranslatedSlugAttributeTrait` | `slug` | Slug generation via `spatie/laravel-sluggable`. |
| (author) | `HasAuthorAttributeTrait` | - | `author_id` | Author relation; the model and name column are configured as `author_model` / `author_name_column`. |
| `HasParent` | `HasParentTrait` | - | `parent_id` | Parent-child content (subpages). See the package's `documentation/parent-child.md` for nested URLs. |
| (hero CTAs) | `HasHeroCallToActionsTrait` | `HasTranslatedHeroCallToActionsTrait` | `hero_call_to_actions` (json) | Call-to-action buttons on the hero. |
| `HasMediaAttributes` | `HasMediaAttributesTrait` (already included by the hero/overview/SEO traits) | - | - | Media helper methods. Needs `InteractsWithMedia` alongside it. |
| `HasTranslatableMedia` | `HasTranslatedAttributesTrait` | - | - | Per-locale media. Add when images must differ per language. |
| `Linkable` | none - implement yourself | - | - | `getViewUrl()` and `getPreviewUrl()`. Needed for dynamic call-to-action links and the table's `ViewAction`. |

`content_blocks` is `json` and cannot carry a default on MySQL below 8.

## Choosing a content blocks trait

```php
// all blocks from config('filament-flexible-content-blocks.default_flexible_blocks'):
use HasDefaultContentBlocksTrait;

// an explicit list, in picker order:
use HasContentBlocksTrait;

public static function registerContentBlocks(): array
{
    return [
        TextImageBlock::class,
        ImageBlock::class,
        FaqBlock::class, // your own block
    ];
}
```

A model using `HasDefaultContentBlocksTrait` can still declare `registerContentBlocks()` on the class
itself - a class method overrides the trait's.

`registerContentBlocks()` does double duty: it fills the builder **and** registers each block's media
collections and conversions when the model boots. A block that is not listed there therefore also never
gets its media collection.

## Example: minimal model with content blocks only

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasDefaultContentBlocksTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Concerns\HasMediaAttributesTrait;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasMediaAttributes;

class Article extends Model implements HasContentBlocks, HasMedia, HasMediaAttributes
{
    use HasDefaultContentBlocksTrait;
    // the content blocks trait does not pull these in, unlike the hero/overview/SEO traits:
    use HasMediaAttributesTrait;
    use InteractsWithMedia;
}
```

## Example: page-like model

See `example/app/Models/Page.php` and `example/app/Models/TranslatablePage.php` in the package for a
model with hero, intro, overview, SEO, slug, author and content blocks.
