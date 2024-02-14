<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\AuthorField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroImageSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\OverviewFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\PublicationSection;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\SEOFields;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\IntroField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SlugField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Actions\PublishAction;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\PublishedColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\TitleColumn;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Filters\PublishedFilter;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                TitleField::create(true),
                                SlugField::create(),
                                PublicationSection::create(),
                                AuthorField::create(),
                                HeroImageSection::create(),
                                IntroField::create(),
                            ]),
                        Tab::make('Content')
                            ->schema([
                                ContentBlocksField::create(),
                            ]),
                        Tab::make('Overview')
                            ->schema([
                                OverviewFields::create(),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                SEOFields::create(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
