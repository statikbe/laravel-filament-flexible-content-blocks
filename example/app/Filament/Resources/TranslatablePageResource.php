<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslatablePageResource\Pages;
use App\Models\TranslatablePage;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions\CopyContentBlocksToLocalesAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\AuthorField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroCallToActionSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroImageSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\OverviewFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\PublicationSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\SEOFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\IntroField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SlugField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\PublishAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\ViewAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\PublishedColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\TitleColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters\PublishedFilter;

class TranslatablePageResource extends Resource
{
    use Translatable;

    protected static ?string $model = TranslatablePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Heading')
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                TitleField::create(),
                                CodeField::create(),
                                SlugField::create(false),
                                IntroField::create(),
                                AuthorField::create(),
                                HeroImageSection::create(true),
                                HeroCallToActionSection::create(),
                                PublicationSection::create(),
                            ]),
                        Tab::make('Content')
                            ->schema([
                                CopyContentBlocksToLocalesAction::make('translate_blocks'),
                                ContentBlocksField::create(),
                            ]),
                        Tab::make('Overview')
                            ->schema([
                                OverviewFields::create(1, true),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                SEOFields::create(1, true),
                            ]),
                    ]),

            ]);
    }

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
            ->actions([
                Tables\Actions\EditAction::make(),
                PublishAction::make(),
                ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslatablePages::route('/'),
            'create' => Pages\CreateTranslatablePage::route('/create'),
            'edit' => Pages\EditTranslatablePage::route('/{record}/edit'),
        ];
    }
}
