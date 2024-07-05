<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\State;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Livewire\Component;

class ViewProduct extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public Product $product;

    public function addToCart(): Action
    {
        return Action::make('addToCart')
            ->color('gray')
            ->size('xl')
            ->hiddenLabel()
            ->icon('heroicon-s-shopping-cart')
            ->action(function () {
                if (!auth()->check())
                    return redirect('admin/login');

                //cek jika product sudah ditambahkan tambahkan stok saja
                $item = OrderItem::where('order_id', null)
                    ->where('product_id', $this->product->id)
                    ->where('user_id', auth()->id())
                    ->first();

                if ($item != []) {
                    $item->qty += 1;
                    $item->save();
                } else
                    OrderItem::create([
                        'product_id' => $this->product->id,
                        'user_id' => auth()->id(),
                        'product' => $this->product->nama,
                        'harga' => $this->product->harga,
                        'qty' => 1,
                    ]);

                return \Filament\Notifications\Notification::make()
                    ->title('Item ditambahkan')
                    ->icon('heroicon-s-shopping-cart')
                    ->iconColor('success')
                    ->send();
            });
    }

    public function buy(): Action
    {
        return Action::make('buy')
            ->color('primary')
            ->size('xl')
            ->label('Buy now')
            ->extraAttributes([
                'class' => 'w-full',
            ])
            ->action(function () {
                if (!auth()->check())
                    return redirect('admin/login');

                $order = Order::create([
                    'user_id' => auth()->id(),
                ]);

                OrderItem::create([
                    'product_id' => $this->product->id,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'product' => $this->product->nama,
                    'harga' => $this->product->harga,
                    'qty' => 1,
                ]);

                return redirect('checkout/' . $order->id);
            });
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}
