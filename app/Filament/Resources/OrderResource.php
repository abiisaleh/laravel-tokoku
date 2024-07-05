<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;

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
                                ->link()
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
                    ->relationship('items')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->columns()
                    ->addable(false)
                    ->deletable(false)
                    ->orderColumn(false)
                    ->schema([
                        TextInput::make('product')
                            ->readOnly(),
                        Group::make([
                            TextInput::make('harga')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->prefix('Rp')
                                ->readOnly(),
                            TextInput::make('qty')
                                ->readOnly(),
                            TextInput::make('subtotal')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->prefix('Rp')
                                ->readOnly(),
                        ])->columns(3)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::where('total', '!=', 0))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->description(fn (Order $record) => $record->tujuan),
                Tables\Columns\TextColumn::make('total')->numeric()->prefix('Rp '),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
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
                    Tables\Actions\ExportBulkAction::make()
                        ->color('success')
                        ->exporter(OrderExporter::class),
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
        return static::getModel()::where('total', '!=', 0)->where('status', 'new')->count();
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
