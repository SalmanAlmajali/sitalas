<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TambahSuratKeluar;

class ChartSuratKeluar extends ChartWidget
{
    protected ?string $heading = 'Surat Keluar per Bulan';

    protected function getData(): array
    {
        $data = collect(range(1, 12))->map(function ($month) {
            return TambahSuratKeluar::whereMonth('tanggal_surat', $month)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Surat Keluar',
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
        return 'bar';
    }
}