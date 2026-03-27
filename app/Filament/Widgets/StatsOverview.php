<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\SuratMasuk;
use App\Models\TambahSuratKeluar;
use App\Models\Proposal;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Surat Masuk', SuratMasuk::count())
                ->description('Total surat masuk')
                ->color('primary'),

            Stat::make('Surat Keluar', TambahSuratKeluar::count())
                ->description('Total surat keluar')
                ->descriptionIcon(Heroicon::ArrowDownLeft, IconPosition::Before)
                ->chart(
                    TambahSuratKeluar::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
                    ->whereYear("created_at", now()->year)
                    ->groupBy("month")
                    ->orderBy("month")
                    ->pluck("count")
                    ->toArray()
                )
                ->descriptionColor('success')
                ->color('success'),

            Stat::make('Proposal', Proposal::count())
                ->description('Total proposal')
                ->descriptionIcon(Heroicon::ArrowDownLeft, IconPosition::Before)
                ->chart(
                    Proposal::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
                    ->whereYear("created_at", now()->year)
                    ->groupBy("month")
                    ->orderBy("month")
                    ->pluck("count")
                    ->toArray()
                )
                ->descriptionColor('warning')
                ->color('warning'),

            Stat::make('User', User::count())
                ->description('Total user')
                ->descriptionIcon(Heroicon::ArrowDownLeft, IconPosition::Before)
                ->chart(
                    User::selectRaw("MONTH(created_at) as month, COUNT(*) as count")
                    ->whereYear("created_at", now()->year)
                    ->groupBy("month")
                    ->orderBy("month")
                    ->pluck("count")
                    ->toArray()
                )
                ->descriptionColor('success')
                ->color('success'),
        ];
    }
}