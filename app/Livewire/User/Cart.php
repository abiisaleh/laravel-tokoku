<?php

namespace App\Livewire\User;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
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

    public ?Order $order;

    public ?array $data = [];

    public function mount(): void
    {
        $this->order = Order::where('status', 'pending')->first();

        if ($this->order)
            $this->data = Order::where('status', 'pending')->first()->toArray();
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
            ->modalSubmitAction($this->data == [] ? false : null)
            ->modalCancelAction(false)
            ->form(function () {

                if ($this->data == [])
                    $form = [
                        Placeholder::make('Product belum ditambahkan')
                    ];

                else
                    $form = [
                        Repeater::make('items')
                            ->hiddenLabel()
                            ->addable(false)
                            ->columns(3)
                            ->reorderable(false)
                            ->afterStateUpdated(function ($state) {
                                $this->order->items = $state;
                                $this->order->save();
                                $this->mount();
                            })
                            ->schema([
                                TextInput::make('harga')->disabled()->prefix('Rp'),

                                TextInput::make('qty')
                                    ->numeric()
                                    ->maxValue(10)
                                    ->minValue(1)
                                    ->live(),

                                Placeholder::make('subtotal')
                                    ->content(fn (Get $get) => 'Rp ' . number_format($get('qty') *  $get('harga')))
                                    ->extraAttributes(['class' => 'font-semibold py-2'])
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['product'] ?? null),
                    ];

                return $form;
            })
            ->action(function () {
                return redirect('checkout/' . $this->data['id']);
            });
    }

    public function render()
    {
        return view('livewire.user.cart');
    }
}
