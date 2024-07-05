<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $totalIncome = Order::all()->sum('total');
        $users = User::all()->count() - 1;
        $orders = Order::where('total', '!=', 0)->count();


        return [
            Stat::make('Total income', number_format($totalIncome))->icon('heroicon-s-banknotes'),
            Stat::make('Order count', number_format($orders))->icon('heroicon-s-shopping-cart'),
            Stat::make('Users', number_format($users))->icon('heroicon-s-user'),

        ];
    }
}
