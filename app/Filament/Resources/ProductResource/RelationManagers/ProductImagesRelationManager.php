<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'ProductImages';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->translateLabel('Image')
                    ->required()
                    ->directory('product-images')
                    ->openable()
                    ->multiple()->reorderable()->appendFiles(),
                Forms\Components\TextInput::make('display_order')
                    ->required()
                    ->translateLabel('Display_order')
                    ->numeric()
                    ->default(0),
                // FileUpload::make('image')
                //     ->directory('listings')
                //     ->image()
                //     ->openable()
                //     ->multiple()->reorderable()->appendFiles()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image'),
                // Tables\Columns\TextColumn::make('image'),
                Tables\Columns\TextColumn::make('display_order'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
