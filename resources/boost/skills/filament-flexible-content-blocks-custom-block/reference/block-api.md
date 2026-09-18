# `AbstractContentBlock` API reference

`Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock` extends
`Illuminate\View\Component`. A block is therefore two things at once: a Filament builder block (its
form) and a Blade component (its front-end rendering).

Its sibling `AbstractFilamentFlexibleContentBlock` is **package-internal** - see the SKILL.md. Custom
blocks always extend `AbstractContentBlock`.

## Constructor

```php
public function __construct(Model&HasContentBlocks&HasMedia $record, ?array $blockData)
```

- `$record` is the model the blocks belong to; available as `$this->record`.
- `$blockData` is the block's saved JSON `data` array; available as `$this->blockData`.
- The parent constructor generates and stores the block id (`BlockIdField::FIELD`, i.e. the
  `block_id` field added to every block schema) when it is missing. **Call it first** - media lookups
  use `getBlockId()`.

```php
public function __construct(Model&HasContentBlocks&HasMedia $record, ?array $blockData)
{
    parent::__construct($record, $blockData);

    $this->title = $blockData['title'] ?? null;
    $this->questions = $blockData['questions'] ?? [];
}
```

## Required (abstract) members

| Member | Purpose |
| --- | --- |
| `public static function getName(): string` | Unique block identifier. Persisted as the `type` key in the `content_blocks` JSON **and** used as the media collection name. Keep it stable; renaming orphans existing content and images. Lowercase, no spaces, e.g. `'faq'`. |
| `public static function getIcon(): \BackedEnum\|string` | Icon in the block picker: a `Filament\Support\Icons\Heroicon` case or an icon name string. |
| `public static function getLabel(): string` | Translated label in the block picker and block header. |
| `public static function getFieldLabel(string $field): string` | Translated label for one form field. Typically `trans("content-blocks.faq.{$field}")`. |
| `protected static function makeFilamentSchema(): array\|Closure` | The block's Filament form components (or a closure returning them). The hidden block-id field is prepended for you. |
| `public function render()` | Returns a `View`, `Closure` or string for the front-end. |
| `public function getSearchableContent(): array` | Array of plain text strings, used by `$model->getSearchableBlockContent()`. |

## Overridable members

| Member | Default | Override when |
| --- | --- | --- |
| `public static function getContextualLabel(?array $state): ?string` | `null` | You want the collapsed block header to show its content, e.g. the title. Return `null` to fall back to `getLabel()`. |
| `public static function make(): Block` | Builds a `ContentBlockWithPreview` with the label, icon, schema, visibility and block class | Almost never; only for exotic builder configuration. |
| `public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void` | no-op | The block has image uploads. See below. |
| `public static function visible(): bool\|Closure` | `true` | The block should be hidden from the picker conditionally (permissions, feature flag). |
| `public static function hasBlockStyles(): bool\|Closure` | from config | You want to force block styles on/off regardless of config. |
| `public static function getPreviewView(): string` | the package's preview wrapper | You need a custom preview shell in the Filament builder. |

## Inherited helpers

| Helper | Use |
| --- | --- |
| `getBlockId(): string` | The block instance's uuid. Needed for media lookups and for unique DOM ids in the view. |
| `replaceParameters(?string $content): ?string` | Applies the configured `text_parameter_replacer` (`:name` style placeholders) to text. Call it on every user-entered text you echo in the view. |
| `addSearchableContent(array &$searchable, ?string $text): array` | Appends non-empty text to the searchable array. |
| `self::CONVERSION_CROP` / `self::CONVERSION_CONTAIN` | The two standard media conversion names. |

## The form schema

`makeFilamentSchema()` returns plain Filament 4/5 schema components. Use `static::getFieldLabel()` for
labels so all labels stay in one translation file.

```php
protected static function makeFilamentSchema(): array|Closure
{
    return [
        TextInput::make('title')
            ->label(static::getFieldLabel('title'))
            ->maxLength(255),
        Repeater::make('questions')
            ->label(static::getFieldLabel('questions'))
            ->schema([
                TextInput::make('question')->label(static::getFieldLabel('question'))->required(),
                RichEditor::make('answer')->label(static::getFieldLabel('answer'))->required(),
            ]),
        Grid::make(2)->schema([
            BackgroundColourField::create(static::class),
            BlockStyleField::create(static::class),
        ]),
    ];
}
```

Note the package's block fields take the block class as argument, because their options and defaults can
be configured per block under `block_specific` in the config.

## Rendering

`render()` returns the view; the data passed to it is Laravel's component data - every **public
property** and every **public method** of the block class is available in the Blade view (methods as
closures).

```php
public function render()
{
    return view('content-blocks.faq');
}
```

With block styles (trait `HasBlockStyle`), append the style suffix so each style can have its own view:

```php
public function render()
{
    return view('content-blocks.faq'.$this->getBlockStyleTemplateSuffix());
}
```

`getBlockStyleTemplateSuffix()` returns `''` for the default style and `'-{style}'` otherwise, so
`resources/views/content-blocks/faq.blade.php` and `faq-compact.blade.php`.

## Images

Use the trait `HasImage` plus `BlockSpatieMediaLibraryFileUpload`, whose `collection()` must equal
`static::getName()`. That field tags uploaded media with the block id, so several instances of the same
block on one page keep their own images.

```php
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns\HasImage;

// in makeFilamentSchema():
BlockSpatieMediaLibraryFileUpload::make('image')
    ->collection(static::getName())
    ->label(static::getFieldLabel('image'))
    ->maxFiles(1),

// register the collection and its conversions:
public static function addMediaCollectionAndConversion(HasMedia&HasMediaAttributes $record): void
{
    $record->addMediaCollection(static::getName())
        ->registerMediaConversions(function (Media $media) use ($record) {
            static::addCropImageConversion($record, 1200, 630);
            static::addContainImageConversion($record, 1200, 630);

            // thumbnail for the Filament upload field:
            $record->addFilamentThumbnailMediaConversion();
        });
}

// expose the image to the view:
public function getImageMedia(?string $conversion = null, array $attributes = []): ?HtmlableMedia
{
    return $this->getHtmlableMedia($this->getBlockId(), $conversion ?? self::CONVERSION_CROP, $this->imageTitle, $attributes);
}

public function getImageUrl(?string $conversion = null): ?string
{
    return $this->getMediaUrl(blockId: $this->getBlockId(), conversion: $conversion ?? self::CONVERSION_CROP);
}
```

`addMediaCollectionAndConversion()` is called by `HasContentBlocksTrait` for every registered block when
the model boots - which is why a block that is not registered on the model also never gets its media
collection.

Conversion sizes can be extended per block class from the config under
`image_conversions.flexible_blocks.specific`.

## How the pieces fit together

- `HasContentBlocksTrait::getFilamentContentBlocks()` calls `YourBlock::make()` for every class in
  `registerContentBlocks()` and hands the result to `ContentBlocksField` (a Filament `Builder`).
- On the front-end, `<x-flexible-content-blocks :page="$page" />` resolves
  `View\Components\ContentBlocks`, which maps each stored `type` back to its block class via
  `getName()`, instantiates it with `($page, $blockData['data'])` and renders it.
- A stored block whose `type` no longer matches any registered block class is silently skipped.
