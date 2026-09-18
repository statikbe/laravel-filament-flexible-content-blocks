# Filament resource reference

The package targets Filament 4/5: `form(Schema $schema): Schema` with `->components([...])`, schema
components from `Filament\Schemas\Components`, and `recordActions()` / `toolbarActions()` on tables.

Form fields live in `Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields`, grouped fields in
`...\Fields\Groups`, table classes in `...\Filament\Table\{Columns,Filters,Actions}`.

Most fields expose a `create()` instead of `make()`, because the field name must match the column the
model traits expect.

## Form

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\AuthorField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroCallToActionSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroImageSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\OverviewFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\PublicationSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\SEOFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\IntroField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ParentField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SlugField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;

public static function form(Schema $schema): Schema
{
    return $schema->components([
        Tabs::make()
            ->columnSpan(2)
            ->tabs([
                Tab::make('General')->schema([
                    TitleField::create(required: true),
                    SlugField::create(),
                    PublicationSection::create(),
                    AuthorField::create(),
                    HeroImageSection::create(),
                    HeroCallToActionSection::create(),
                    IntroField::create(),
                    ParentField::create(),
                ]),
                Tab::make('Content')->schema([
                    ContentBlocksField::create(),
                ]),
                Tab::make('Overview')->schema([
                    OverviewFields::create(columns: 1),
                ]),
                Tab::make('SEO')->schema([
                    SEOFields::create(columns: 1),
                ]),
            ]),
    ]);
}
```

Add only the fields whose traits the model actually uses. Groups (`HeroImageSection`,
`PublicationSection`, `OverviewFields`, `SEOFields`, `HeroCallToActionSection`) are convenience
wrappers - the individual fields (`HeroImageField`, `SEOTitleField`, ...) are available if you want a
different layout.

Translatable images: pass `translatableImage: true` to `HeroImageSection::create()`,
`OverviewFields::create()` and `SEOFields::create()`. `HeroImageSection::create()` also takes
`enableVideoUrlField: true` for the hero video.

### `ContentBlocksField`

`ContentBlocksField::create()` builds the Filament `Builder`. It resolves the available blocks from the
Livewire page's resource model (`getFilamentContentBlocks()`), so the block list is configured on the
**model**, not here. It also applies the package's builder defaults: block previews (config
`block_preview`), slide-over edit action, block icons, reordering, collapsing, and collapse/expand-all
buttons. Change which blocks appear by changing the model's `registerContentBlocks()`.

## Table

```php
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\PublishAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\ReplicateAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\ViewAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\PublishedColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\TitleColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters\PublishedFilter;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TitleColumn::create(),
            PublishedColumn::create(),
        ])
        ->filters([
            PublishedFilter::create(),
        ])
        ->recordActions([
            EditAction::make(),
            PublishAction::make(),
            ViewAction::make(),      // requires the model to implement Linkable
            ReplicateAction::make(), // copies attributes and all media of the record
        ])
        ->toolbarActions([
            DeleteBulkAction::make(),
        ]);
}
```

## Translatable resources

For a translatable model:

1. Add `Statikbe\FilamentFlexibleContentBlocks\Filament\Resource\Concerns\FlexibleContentBlocksTranslatable`
   to the resource, so it uses the locales from `config('filament-flexible-content-blocks.supported_locales')`.
2. Follow the `filament/spatie-laravel-translatable-plugin` setup for the resource and its pages.
3. When the model has translatable images, use the package's page traits instead of the plugin's:
   - `Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\CreateRecord\Concerns\TranslatableWithMedia`
   - `Statikbe\FilamentFlexibleContentBlocks\Filament\Pages\EditRecord\Concerns\TranslatableWithMedia`

### Translated slugs

A new record can exist without a slug in every locale, and Filament resolves the route binding by slug.
Switching to a locale without a translated slug then errors. Bind by id instead:

```php
protected static ?string $recordRouteKeyName = 'id';

public static function getPages(): array
{
    return [
        'index' => Pages\ListArticles::route('/'),
        'create' => Pages\CreateArticle::route('/create'),
        'edit' => Pages\EditArticle::route('/{record:id}/edit'),
    ];
}
```
