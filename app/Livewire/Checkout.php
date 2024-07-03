<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Setting;
use App\Models\State;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Livewire\Component;

class Checkout extends Component implements HasForms
{
    use InteractsWithForms;

    public ?Order $order;

    public ?array $data = [];

    public $title = '';

    public function mount(): void
    {
        $this->title = 'Checkout #' . $this->order->id;
        $this->data = $this->order->toArray();
        $this->data['alamat'] = auth()->user()->alamat;
        $this->data['kecamatan'] = auth()->user()->state_id;

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Wizard::make([
                    Step::make('Order')
                        ->icon('heroicon-m-shopping-bag')
                        ->schema([
                            Repeater::make('items')
                                ->addable(false)
                                ->columns(3)
                                ->reorderable(false)
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
                            Fieldset::make('Summary')
                                ->columns()
                                ->schema([
                                    Placeholder::make('total')
                                        ->extraAttributes(['class' => 'mr-auto'])
                                        ->inlineLabel()
                                        ->content(function () {
                                            $subtotal = 0;

                                            foreach ($this->data['items'] as $items)
                                                $subtotal += $items['harga'] * $items['qty'];

                                            return 'Rp ' . number_format($subtotal);
                                        }),
                                ])
                        ]),
                    Step::make('Delivery')
                        ->icon('heroicon-m-truck')
                        ->schema([
                            Select::make('kecamatan')
                                ->required()
                                ->options(fn () => State::all()->pluck('kecamatan', 'id')->toArray())
                                ->live(),

                            Textarea::make('alamat')
                                ->required(),
                        ]),
                    Step::make('Billing')
                        ->icon('heroicon-m-credit-card')
                        ->schema([
                            Fieldset::make('Summary')->columns(3)->schema([
                                Placeholder::make('subtotal_product')
                                    ->content(function () {
                                        $subtotal = 0;

                                        foreach ($this->data['items'] as $items)
                                            $subtotal += $items['harga'] * $items['qty'];

                                        return 'Rp ' . number_format($subtotal);
                                    }),

                                Placeholder::make('ongkos_kirim')
                                    ->content(fn (Get $get) => 'Rp ' . number_format(State::find($get('kecamatan'))->ongkir ?? 0)),

                                Placeholder::make('total')
                                    ->content(function (Get $get) {
                                        $subtotal = 0;

                                        foreach ($this->data['items'] as $items)
                                            $subtotal += $items['harga'] * $items['qty'];

                                        $ongkir = State::find($get('kecamatan'))->ongkir ?? 0;

                                        $total = $subtotal + $ongkir;

                                        return 'Rp ' . number_format($total);
                                    }),
                                Placeholder::make('metode_pembayaran')
                                    ->content('Transfer Bank'),
                                Placeholder::make('bank')
                                    ->content(Setting::where('key', 'bank')->first()->value ?? ''),
                                Placeholder::make('no_rekening')
                                    ->content(Setting::where('key', 'rekening')->first()->value ?? ''),
                            ]),

                            FileUpload::make('bukti_pembayaran')
                                ->required()
                                ->image()
                                ->helperText('Harap mengupload bukti transfer pesanan ke nomomor rekening diatas')
                        ])
                ])->submitAction($this->getSubmitAction())
            ]);
    }

    public function getSubmitAction(): Action
    {
        return Action::make('submit')
            ->extraAttributes([
                'type' => 'submit',
                'wire:click' => 'submit',
            ]);
    }

    public function submit()
    {
        $subtotal = 0;

        foreach ($this->data['items'] as $items)
            $subtotal += $items['harga'] * $items['qty'];

        $order = [
            'status' => 'new',
            'subtotal' => $subtotal,
            'ongkir' => State::find($this->form->getState()['kecamatan'])->ongkir,
            'tujuan' => $this->form->getState()['alamat'] . ', Kec. ' . State::find($this->form->getState()['kecamatan'])->kecamatan,
            'items' => $this->data['items'],
            'bukti_pembayaran' => $this->form->getState()['bukti_pembayaran'],
        ];

        $this->order->update($order);

        User::find(auth()->id())->update([
            'state_id' => $this->form->getState()['kecamatan'],
            'alamat' => $this->form->getState()['alamat'],
        ]);

         \Filament\Notifications\Notification::make()
            ->title('Pesanan berhasil dibuat')
            ->icon('heroicon-s-shopping-cart')
            ->iconColor('success')
            ->send();
            
            return redirect('/user/order/'.$this->order->id);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
