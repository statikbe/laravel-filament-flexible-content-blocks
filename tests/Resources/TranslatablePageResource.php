<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\IntroField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SlugField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\TitleColumn;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages\CreateTranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages\EditTranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages\ListTranslatablePages;

class TranslatablePageResource extends Resource
{
    use Translatable;

    protected static ?string $model = TranslatablePage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                CodeField::create(true),
                                TitleField::create(true),
                                SlugField::create(),
                                IntroField::create(),
                            ]),
                        Tab::make('Content')
                            ->schema([
                                ContentBlocksField::create(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TitleColumn::create(),
                TextColumn::make('slug'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => ListTranslatablePages::route('/'),
            'create' => CreateTranslatablePage::route('/create'),
            'edit' => EditTranslatablePage::route('/{record}/edit'),
        ];
    }
}
