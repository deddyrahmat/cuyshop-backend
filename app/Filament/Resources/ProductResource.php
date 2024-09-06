<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;
use stdClass;
use Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationLabel(): string
    {
        return __('Product');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Status Product')->schema([
                    Forms\Components\Toggle::make('published')
                        ->translateLabel('Published')
                        ->required()
                ])->visible(fn($livewire) => $livewire instanceof Pages\EditProduct),

                Forms\Components\TextInput::make('title')
                    ->translateLabel('Title')
                    ->required()
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->live(debounce: 250),
                Forms\Components\TextInput::make('slug')->disabled(),
                Forms\Components\Select::make('category_id')
                    ->translateLabel('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->translateLabel('Quantity')
                    ->numeric(),
                RichEditor::make('description')
                    ->translateLabel('Description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->translateLabel('Price')
                    ->numeric()
                    ->prefix('Rp.'),
                Forms\Components\TextInput::make('weight')
                    ->required()
                    ->translateLabel('Weight')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('title')
                    ->numeric()
                    ->translateLabel('Title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->translateLabel('Quantity')
                    ->sortable(),
                Tables\Columns\IconColumn::make('published')
                    ->translateLabel('Published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->translateLabel('Price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->translateLabel('Updated_by')
                    ->numeric()
                    ->sortable(),
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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('Product');
    }
    public static function getPluralModelLabel(): string
    {
        return __('Products');
    }
}
