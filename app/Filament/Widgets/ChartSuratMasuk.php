<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Penerima;
use Carbon\Carbon;

class ChartSuratMasuk extends ChartWidget
{
    protected ?string $heading = 'Surat Masuk per Bulan';

    protected function getData(): array
    {
        $data = collect(range(1, 12))->map(function ($month) {
            return Penerima::whereMonth('tanggal_terima', $month)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Surat Masuk',
                    'data' => $data,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}