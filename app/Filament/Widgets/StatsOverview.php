<?php

namespace App\Filament\Widgets;

use App\Models\Properti;
use App\Models\Sewa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->descriptionIcon('heroicon-m-users'),
            Stat::make('Pemilik', User::where('role', 'pemilik')->count())
                ->descriptionIcon('heroicon-m-user-circle'),
            Stat::make('Penyewa', User::where('role', 'penyewa')->count())
                ->descriptionIcon('heroicon-m-user-group'),
            Stat::make('Total Properti', Properti::count())
                ->descriptionIcon('heroicon-m-home'),
            Stat::make('Total Sewa', Sewa::count())
                ->descriptionIcon('heroicon-m-document-text'),
            Stat::make('Pendapatan Biaya Layanan', 'Rp '.number_format(Sewa::sum('biaya_layanan'), 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes'),

        ];
    }
}
