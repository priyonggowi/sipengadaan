<?php

namespace App\Filament\Widgets;

use App\Models\PurchaseRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PurchaseChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran per Bulan (Realisasi)';

    protected function getData(): array
    {
        // Get actual total spent per month for the current year
        $data = PurchaseRequest::select(
            DB::raw('sum(total_actual) as sums'),
            DB::raw("strftime('%m', created_at) as month")
        )
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['purchased', 'received'])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $sums = array_fill(0, 12, 0);
        foreach ($data as $item) {
            $monthIndex = intval($item->month) - 1;
            $sums[$monthIndex] = $item->sums;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran (Rp)',
                    'data' => $sums,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
