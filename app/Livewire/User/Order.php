<?php

namespace App\Livewire\User;

use App\Models\Order as ModelsOrder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Livewire\Component;

class Order extends Component implements HasForms, HasInfolists
{

    use InteractsWithForms;
    use InteractsWithInfolists;

    public ModelsOrder $order;

    public function productInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->order)
            ->schema([
                Split::make([
                    RepeatableEntry::make('items')
                        ->schema([
                            TextEntry::make('product'),
                            TextEntry::make('qty'),
                            TextEntry::make('harga'),
                        ])
                        ->columns(3),
                    Section::make('Order detail')
                        ->schema([
                            TextEntry::make('tujuan'),
                            TextEntry::make('subtotal')->numeric()->prefix('Rp '),
                            TextEntry::make('ongkir')->numeric()->prefix('Rp '),
                            ImageEntry::make('bukti_pembayaran')
                        ])
                ])

            ]);
    }

    public function render()
    {
        return view('livewire.user.order');
    }
}
