<?php

namespace App\Filament\Pages;

use App\Models\Penerima;
use App\Models\UnitPengolah;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use UnitEnum;

class JumlahSuratMasuk extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Jumlah Surat Masuk';
    protected static ?string $title = 'Report Jumlah Surat Masuk';
    protected static ?string $slug = 'report-jumlah-surat-masuk';
    protected static string | UnitEnum | null $navigationGroup = 'Report';

    protected string $view = 'filament.pages.jumlah-surat-masuk';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UnitPengolah::query()->whereRaw('1 = 0')
            )
            ->columns([
                Tables\Columns\TextColumn::make('direktorat')->label('Direktorat'),
            ])
            ->paginated(false)
            ->filters([
                Filter::make('tanggal')
                    ->label('Filter Tanggal')
                    ->schema([
                        DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),

                        DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),
                    ])
                    ->columns(2),
            ])
            ->filtersFormColumns(1);
    }

    public function getTanggalDari(): ?string
    {
        return data_get($this->tableFilters, 'tanggal.tanggal_dari');
    }

    public function getTanggalSampai(): ?string
    {
        return data_get($this->tableFilters, 'tanggal.tanggal_sampai');
    }

    public function hasFilterTanggal(): bool
    {
        return filled($this->getTanggalDari()) && filled($this->getTanggalSampai());
    }

    public function getGroupedSuratProperty(): Collection
    {
        if (! $this->hasFilterTanggal()) {
            return collect();
        }

        $tanggalDari = $this->getTanggalDari();
        $tanggalSampai = $this->getTanggalSampai();

        $units = UnitPengolah::query()
            ->orderBy('urutan')
            ->get();

        $suratByDirektorat = Penerima::query()
            ->with([
                'unitPengolah',
                'kodeSurat',
                'sifatSurat',
            ])
            ->whereDate('tanggal_terima', '>=', $tanggalDari)
            ->whereDate('tanggal_terima', '<=', $tanggalSampai)
            ->orderBy('tanggal_surat')
            ->orderBy('no_urut')
            ->get()
            ->groupBy('direktorat_id');

        return $units->map(function ($unit) use ($suratByDirektorat) {
            $items = $suratByDirektorat->get($unit->id, collect());

            return (object) [
                'unit_id' => $unit->id,
                'direktorat' => $unit->direktorat,
                'jumlah_surat' => $items->count(),
                'items' => $items,
            ];
        });
    }
}