<?php

namespace App\Livewire\User;

use App\Models\Order as ModelsOrder;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
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
use Filament\Notifications\Notification;
use Livewire\Component;

class Order extends Component implements HasForms, HasActions
{

    use InteractsWithForms;
    use InteractsWithActions;

    public ModelsOrder $order;

    public function getProductImage(int $productId)
    {
        return '/storage/' . Product::find($productId)->gambar;
    }

    public function cancelAction(): Action
    {
        return Action::make('cancel')
            ->requiresConfirmation()
            ->color('danger')
            ->size('lg')
            ->extraAttributes(['class' => 'w-full'])
            ->outlined()
            ->action(function () {
                $this->order->update(['status' => 'canceled']);

                Notification::make()
                    ->title('Order canceled')
                    ->danger()
                    ->send();
            });
    }

    public function downloadAction(): Action
    {
        return Action::make('download')
            ->label('Download bukti pembayaran')
            ->url('/storage/'.$this->order->gambar)
            ->color('primary')
            ->size('lg')
            ->extraAttributes(['class' => 'w-full']);
    }

    public function render()
    {
        return view('livewire.user.order');
    }
}
