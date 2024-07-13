<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Livewire\Component;


class Cart extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public function mount(): void
    {
        $items = OrderItem::whereNull('order_id')->whereBelongsTo(auth()->user())->get();

        if ($items)
            $this->data['items'] = $items->toArray();
    }

    public function cart(): Action
    {
        return Action::make('cart')
            ->icon('heroicon-o-shopping-cart')
            ->iconButton()
            ->color('gray')
            ->extraAttributes(['class' => 'hover:text-white'])
            ->size('xl')
            ->hiddenLabel()
            ->fillForm($this->data)
            ->modalSubmitActionLabel('Checkout')
            ->modalSubmitAction($this->data['items'] == [] ? false : null)
            ->modalCancelAction(false)
            ->form(function () {
                $this->mount();

                if ($this->data['items'] == [])
                    $form = [
                        Placeholder::make('Product belum ditambahkan')
                    ];

                else
                    $form = [
                        Repeater::make('items')
                            ->afterStateUpdated(function ($state) {

                                $items = OrderItem::whereNull('order_id')->whereBelongsTo(auth()->user())->get();

                                foreach ($items as $item) {
                                    if ($state == [])
                                        return OrderItem::whereNull('order_id')->whereBelongsTo(auth()->user())->delete();

                                    foreach ($state as $cart) {
                                        if ($item->id != $cart['id'])
                                            $item->delete();
                                    }
                                }

                                $this->mount();
                            })
                            ->hiddenLabel()
                            ->addable(false)
                            ->columns(3)
                            ->reorderable(false)
                            ->key('id')
                            ->schema([
                                Hidden::make('id'),

                                TextInput::make('harga')
                                    ->prefix('Rp')
                                    ->readOnly(),

                                TextInput::make('qty')
                                    ->numeric()
                                    ->readOnly(),

                                Placeholder::make('subtotal')
                                    ->content(fn (Get $get) => 'Rp ' . number_format($get('qty') *  $get('harga')))
                                    ->extraAttributes(['class' => 'font-semibold py-2'])
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['product'] ?? null),
                    ];

                return $form;
            })
            ->action(function () {
                $items = OrderItem::where('order_id', null)->whereBelongsTo(auth()->user());

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'subtotal' => $items->sum('subtotal')
                ]);

                $items->update(['order_id' => $order->id]);

                return redirect('checkout/' . $order->id);
            });
    }

    public function render()
    {
        return view('livewire.user.cart');
    }
}
