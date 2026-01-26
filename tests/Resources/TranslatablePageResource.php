<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Resources;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\CodeField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\ContentBlocksField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\IntroField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SlugField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\TitleField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Table\Columns\TitleColumn;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Resources\TranslatablePageResource\Pages;

class TranslatablePageResource extends Resource
{
    use Translatable;

    protected static ?string $model = TranslatablePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Tables\Columns\TextColumn::make('slug'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTranslatablePages::route('/'),
            'create' => Pages\CreateTranslatablePage::route('/create'),
            'edit' => Pages\EditTranslatablePage::route('/{record}/edit'),
        ];
    }
}
