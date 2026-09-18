# Reusable block concerns and form fields

Before writing a feature by hand, check whether the package already provides it. All traits live in
`Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\Concerns`, all form fields in
`Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks`.

Every field's `create()` takes the block class (`static::class`) because its options and defaults can be
overridden per block under `block_specific` in `config/filament-flexible-content-blocks.php`.

## Block style - `HasBlockStyle` + `BlockStyleField`

Lets an editor pick a visual variant of the block; the choice selects a different Blade view.

```php
use HasBlockStyle;

// constructor:
$this->setBlockStyle($blockData);

// makeFilamentSchema():
BlockStyleField::create(static::class),

// render():
return view('content-blocks.faq'.$this->getBlockStyleTemplateSuffix());
```

`getBlockStyleTemplateSuffix()` returns `''` for the `default` style and `-{style}` otherwise, so a
style `compact` needs `resources/views/content-blocks/faq-compact.blade.php`.

Config: `block_styles.enabled_for_all_blocks` and `block_styles.options`, or per block under
`block_specific.{BlockClass}.block_styles`. The field hides itself when styles are disabled or when
fewer than two options are configured. Override `hasBlockStyles()` on the block to force the behaviour.

## Background colour - `HasBackgroundColour` + `BackgroundColourField`

```php
use HasBackgroundColour;

// constructor:
$this->backgroundColourType = $blockData['background_colour'] ?? null;

// makeFilamentSchema():
BackgroundColourField::create(static::class),
```

In the view: `$getBackgroundColourClass()` returns the CSS class configured for the chosen option
(config key `background_colours.options.{key}.class`).

## Images - `HasImage`, `HasImageConversionType` and the image fields

| Trait / field | Gives you |
| --- | --- |
| `HasImage` | `getMedia()`, `getAllMedia()`, `hasImage()`, `getHtmlableMedia()`, `getMediaUrl()`, and the `addCropImageConversion()` / `addContainImageConversion()` helpers for `addMediaCollectionAndConversion()`. |
| `HasImageConversionType` + `ImageConversionTypeField` | Lets the editor choose crop vs contain; `setImageConversionType($blockData)` in the constructor, `getImageConversionType($conversion)` when rendering. |
| `ImagePositionField` | Left / center / right select; options configurable per block. |
| `ImageWidthField` | 100/75/50/33/25% select; options configurable per block. |
| `BlockSpatieMediaLibraryFileUpload` | The upload field. **Always use this instead of Filament's `SpatieMediaLibraryFileUpload`** - it tags media with the block id so multiple instances of the same block keep their own images. Its `->collection()` must be `static::getName()`. |

## Call to action - `HasCallToAction` + `CallToActionField` / `CallToActionRepeater`

Adds a repeater of call-to-action buttons (internal model link, URL, e-mail, ...) with a configurable
button type. Config: `call_to_action_number_of_items` (min/max) and `call_to_action_button_types`,
overridable per block. Render with the `<x-flexible-call-to-action>` component.

## Other building blocks

| Class | Use |
| --- | --- |
| `GridColumnsField` | Number-of-columns select for grid-like blocks. |
| `OverviewItemField` | Select records from the models listed in `overview_models`. |
| `BlockIdField` | The hidden `block_id` field. Added to every block schema automatically - do not add it yourself. |

## Text parameter replacement

Echo every editor-entered text through `$replaceParameters(...)` in the Blade view. It applies the
class configured as `text_parameter_replacer`, which replaces `:placeholder` tokens. When no replacer is
configured the text passes through unchanged, so it is always safe to call.
