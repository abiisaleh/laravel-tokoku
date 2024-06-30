<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\State;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;

class Settings extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Select::make('Bank')
                    ->default(Setting::where('key', 'bank')->first()->value ?? '')
                    ->options([
                        'BCA' => 'Bank Central Asia',
                        'BRI' => 'Bank Rakyat Indonesia',
                        'Mandiri' => 'Bank Mandiri',
                        'BNI' => 'Bank Negara Indonesia',
                    ]),
                TextInput::make('Rekening')
                    ->default(Setting::where('key', 'rekening')->first()->value ?? 'ada')
            ]);
    }

    public function table(Table $table): Table
    {
        $form = [
            TextInput::make('kecamatan'),
            TextInput::make('ongkir')->prefix('Rp'),
        ];

        return $table
            ->heading('Biaya ongkos kirim')
            ->headerActions([
                CreateAction::make()->form($form)
            ])
            ->query(State::query())
            ->columns([
                TextColumn::make('kecamatan'),
                TextColumn::make('ongkir')->prefix('Rp ')->numeric(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make()->form($form),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }
}
