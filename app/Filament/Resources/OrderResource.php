<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\View\Components\UserProfile;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ComponentsActionsAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\View\Component;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([
                            Placeholder::make('user')
                                ->hiddenLabel()
                                ->content(fn (Order $record) => view('components.user-profile', [
                                    'avatar' => filament()->getUserAvatarUrl($record->user),
                                    'nama' => $record->user->name,
                                    'tujuan' => $record->tujuan,
                                ]))
                                ->inlineLabel(),
                        ]),
                    Section::make('Order Summary')
                        ->headerActions([
                            Forms\Components\Actions\Action::make('lihat_bukti_pembayaran')
                                ->openUrlInNewTab()
                                ->url(fn (Order $order) => '/storage/' . $order->bukti_pembayaran)
                        ])
                        ->schema([
                            Placeholder::make('created_at')
                                ->content(fn (Order $record) => $record->created_at->format('d M Y'))
                                ->inlineLabel(),
                            Placeholder::make('subtotal_product')
                                ->content(fn (Order $record) => 'Rp ' . number_format($record->subtotal))
                                ->inlineLabel(),
                            Placeholder::make('ongkir')
                                ->content(fn (Order $record) => 'Rp ' . number_format($record->ongkir))
                                ->inlineLabel(),
                            Placeholder::make('total')
                                ->content(fn (Order $record) => 'Rp ' . number_format($record->total))
                                ->inlineLabel(),
                            Forms\Components\ToggleButtons::make('status')
                                ->options(OrderStatus::class)
                                ->inline()
                                ->required()
                        ]),
                ])
                    ->columnSpanFull()
                    ->columns(),

                Repeater::make('items')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->columns(3)
                    ->addable(false)
                    ->deletable(false)
                    ->orderColumn(false)
                    ->schema([
                        TextInput::make('product')
                            ->readOnly(),
                        TextInput::make('harga')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->prefix('Rp')
                            ->readOnly(),
                        TextInput::make('qty')
                            ->readOnly(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->where('status', '!=', 'pending'))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->description(fn (Order $record) => $record->tujuan),
                Tables\Columns\TextColumn::make('total')->numeric()->prefix('Rp '),
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

    public static function getNavigationBadge(): ?string

    {
        return static::getModel()::where('status', 'new')->count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
