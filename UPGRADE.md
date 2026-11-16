# Upgrades

## v4.3

v4.3 widens the supported dependency ranges. No code changes are needed for most applications, but check the points below.

### spatie/laravel-sluggable 4, 5 and 6 are now supported

The constraint is now `^3.4|^4.0|^5.0|^6.0`. Composer picks the highest version your PHP and Laravel versions allow:
v5 and v6 require PHP 8.4 and Laravel 13, so other setups stay on v3 or v4. Slug generation and route binding of
`HasSlugAttributeTrait` and `HasTranslatedSlugAttributeTrait` behave the same on every version, including resolving
translated slugs in any supported locale.

If your application ends up on sluggable v6, check your own models:

- **UUID or ULID models:** combining a slug trait with Laravel's `HasUuids` or `HasUlids` now causes a trait conflict.
  Use the combined traits from sluggable instead (e.g. `Spatie\Sluggable\HasSlugWithUuids`). See the
  [sluggable upgrade guide](https://github.com/spatie/laravel-sluggable/blob/main/UPGRADING.md).
- **Inherited slug traits:** apply `HasSlugAttributeTrait` or `HasTranslatedSlugAttributeTrait` only once in a model's
  inheritance chain. Re-adding it in a subclass can cause recursive route binding.

### dereuromark/media-embed 1.x is now required

The constraint changed from `^0.6` to `^1.0`. In v1 the `MediaObject` is immutable: `setAttribute()` is replaced by
`withAttribute()`, which returns a new instance. If you extended `VideoBlock` or call `MediaObject::setAttribute()`
yourself, switch to `withAttribute()` and use the returned object. If your application requires `dereuromark/media-embed:^0.6`
directly, update that constraint as well. v1 also adds default iframe attributes (`title`, `loading`, `referrerpolicy`)
to video embeds.

### openai-php/laravel 0.20 to 0.22 are now supported

The constraint is now `^0.19|^0.20|^0.21|^0.22`. The API and config keys this package uses are unchanged.

## v2.1.0

Since v2.1.0 the media UUID is no longer stored in the block data. This has impact on the Cards Block. 
If you are using the Cards Block and have published the views, please edit your custom view and change the 
`$getCardImageMedia()` to the code blow in the Blade view:

```php 
<x-flexible-card :data="$card">
    {!! $getCardImageMedia($card->cardId, $card->title, false, ['class' => 'w-full']) !!}
</x-flexible-card>
```

## v2.0.0

To upgrade to Laravel 11, we needed to migrate to spatie-medialibrary v11, which required an upgrade to spatie-image v3.
The spatie-image library v3 is completely refactored, so the classes and functions we used for image conversions changed.

I was able to keep API quite stable. However, if you update, you need to make some small changes to the image conversions 
in the configuration file:
- The `Manipulations` class no longer exists, and should be replaced with `Fit`. 
- The image format constants have also disappeared, so I added a new enum `ImageFormat` to the package to avoid typos, or
you can also use strings for the file extension, see `ImageFormat` for the supported file types.

Additionally, you can now also use functions to declare image conversions, so you can use the new spatie-image API and
keep your image conversions fully typed. Below is an example:

```
'seo_image' => [
    'seo_image' => function(\Spatie\MediaLibrary\Conversions\Conversion $conversion) {
        return $conversion->fit(Fit::Crop, 1200, 630)
            ->format(ImageFormat::WEBP->value)
            ->withResponsiveImages();
    },
],
'overview_image' => [
    'overview_image' => [
        'fit' => Fit::Crop,
        'width' => 500,
        'height' => 500,
        'responsive' => true,
        'format' => ImageFormat::WEBP->value 
    ],
],
```

## v1.0.0

The content blocks data model has changed. Therefore an automated migration needs to be done.

Spatie Medialibrary uses a polymorph relationship with the model that has the content blocks. But this makes it
difficult to link n images to different blocks of 1 model. 
Each block now has an explicit `block_id` and images in blocks have a link to this `block_id`. 
This way it is much easier to resolve which images need to be cleaned up and which images are linked to
a block and a specific locale. We had several cases of images that were disappearing. This new data model will link images
and blocks tighter together, which makes it possible to correctly delete the unneeded images.

To migrate all your models that make use of content blocks, there is a command you can run. **You need to run this for each
model that has content blocks.** For example:

```shell
php artisan filament-flexible-content-blocks:upgrade-images \\App\\Models\\Page
```

*PS:* make sure you run this command with the user that has access to the file system where the media is stored. 

The command will add `block_id` fields to each block and card of the cards block. And link the images to the block.

In case you have implemented custom blocks with images that are stored in other form field name than `image` (e.g. photo), 
you can add the command flag `--customimage=photo` and this field will also be migrated.

If you have a custom block with a repeater with images inside the repeater blocks, you need to extend the command a bit, 
look at the cards logic in the `UpgradeSpatieImageFieldsCommand` command.

