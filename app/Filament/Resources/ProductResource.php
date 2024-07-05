<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('gambar')->image()->imageCropAspectRatio('1:1')->columnSpanFull()->disk('public')->visibility('public'),
                Forms\Components\TextInput::make('nama')->required(),
                Forms\Components\Select::make('category_id')->relationship('category', 'nama')->required(),
                Forms\Components\TextInput::make('harga')->required()->numeric()->prefix('Rp'),
                Forms\Components\TextInput::make('stok')->required()->default(1)->numeric(),
                Forms\Components\Textarea::make('deskripsi')->required()->columnSpanFull()->rows(5),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')->circular()->grow(false),
                Tables\Columns\TextColumn::make('nama')->searchable()->description(fn (Product $record) => $record->category->nama),
                Tables\Columns\TextColumn::make('harga')->numeric()->prefix('Rp '),
                Tables\Columns\TextColumn::make('stok')->numeric(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'nama')
                    ->preload()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
