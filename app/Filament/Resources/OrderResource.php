<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make()
                    ->schema([
                        Placeholder::make('subtotal_product')->content(fn (Order $record) => number_format($record->subtotal)),
                        Placeholder::make('ongkir')->content(fn (Order $record) => number_format($record->ongkir)),
                        Placeholder::make('total')->content(fn (Order $record) => number_format($record->total)),
Forms\Components\Select::make('status')
->options(OrderStatus::class)
                    ->required()
                    ->native(false)
                    ]),
                Repeater::make('items')
                    ->columns(3)
                    ->addable(false)
                    ->deletable(false)
                    ->orderColumn(false)
                    ->schema([
                        TextInput::make('product')
                            ->disabled(),
                        TextInput::make('harga')
                            ->disabled(),
                        TextInput::make('qty')
                            ->disabled(),
                    ]),
                ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('tujuan'),
                Tables\Columns\TextColumn::make('total'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
