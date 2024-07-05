<?php

namespace App\Livewire\User;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class Orders extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->whereBelongsTo(auth()->user())->where('total', '!=', 0))
            ->columns([
                TextColumn::make('created_at')->since()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('total')->numeric()->prefix('Rp '),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('View')
                    ->icon('heroicon-s-eye')
                    ->url(fn (Order $order) => 'order/' . $order->id)
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.user.orders');
    }
}
