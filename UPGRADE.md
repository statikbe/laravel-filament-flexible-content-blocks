# Upgrades

## v1.0.0

The content blocks data model has changed. Each block now has an explicit `block_id` and images in blocks have a link to
this `block_id`. This way it is much easier to resolve which images need to be cleaned up and which images are linked to
a block and a specific locale. We had several cases of images that were disappearing. This new data model will link images
and blocks better.

To migrate all your models that make use of content blocks, there is a command you can run. You need to run this for each
model that has content blocks. For example:

```shell
php artisan filament-flexible-content-blocks:upgrade-images \\App\\Models\\Page
```

The command will add `block_id` fields to each block and card of the cards block. And link the images to the block.

In case you have implemented custom blocks with images that are stored in other form field name then `image` (e.g. photo), 
you can add the command flag `--customimage=photo` and this field will also be migrated.

If you have a custom block with a repeater with images inside the repeater blocks, you need to extend the command a bit, 
look at the cards logic in the `UpgradeSpatieImageFieldsCommand` command.
