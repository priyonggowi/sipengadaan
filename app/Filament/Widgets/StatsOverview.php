<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengajuan', PurchaseRequest::count())
                ->description('Seluruh permintaan pengadaan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
                
            Stat::make('Menunggu Persetujuan', PurchaseRequest::where('status', 'pending')->count())
                ->description('Perlu direview Manager')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Total Estimasi Biaya', 'Rp ' . number_format(PurchaseRequest::sum('total_estimated'), 0, ',', '.'))
                ->description('Dari semua pengajuan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
