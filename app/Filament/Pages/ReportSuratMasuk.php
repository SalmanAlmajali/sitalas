<?php

namespace App\Filament\Pages;

use App\Models\Penerima;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use App\Filament\Exports\ReportSuratMasukExport;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class ReportSuratMasuk extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Report Surat Masuk';
    protected static ?string $title = 'Report Surat Masuk';
    protected string $view = 'filament.pages.report-surat-masuk';
    protected static string | UnitEnum | null $navigationGroup = 'Report';

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin();
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isAdmin();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns([
                TextColumn::make('tanggal_terima')
                    ->label('Tgl Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('pengirim')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('sifatSurat.sifat_surat')
                    ->label('Sifat Surat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kodeSurat.kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('perihal')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('no_box')
                    ->label('No Box')
                    ->searchable(),

                TextColumn::make('no_rak')
                    ->label('No Rak')
                    ->searchable(),
            ])
            ->defaultSort('tanggal_terima', 'desc')
            ->striped()
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

                Filter::make('unit_pengolah')
                    ->label('Unit Pengolah')
                    ->schema([
                        Select::make('direktorat_id')
                            ->label('Unit Pengolah')
                            ->relationship('unitPengolah', 'direktorat')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Unit'),
                    ]),

                Filter::make('sifat_surat')
                    ->label('Sifat Surat')
                    ->schema([
                        Select::make('sifat_surat_id')
                            ->label('Sifat Surat')
                            ->relationship('sifatSurat', 'sifat_surat')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Sifat'),
                    ]),
            ])
            ->filtersFormColumns(1)
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Pilih minimal satu filter dari tanggal, unit pengolah, atau sifat surat lalu klik Apply filters.');
    }

    protected function getTableQuery(): Builder
    {
        $tanggalDari = data_get($this->tableFilters, 'tanggal.tanggal_dari');
        $tanggalSampai = data_get($this->tableFilters, 'tanggal.tanggal_sampai');
        $direktoratId = data_get($this->tableFilters, 'unit_pengolah.direktorat_id');
        $sifatSuratId = data_get($this->tableFilters, 'sifat_surat.sifat_surat_id');

        $query = Penerima::query()->with([
            'unitPengolah',
            'kodeSurat',
            'sifatSurat',
            'pengarah',
        ]);

        $hasFilter =
            filled($tanggalDari) ||
            filled($tanggalSampai) ||
            filled($direktoratId) ||
            filled($sifatSuratId);

        if (! $hasFilter) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->when(
                filled($tanggalDari),
                fn (Builder $query) => $query->whereDate('tanggal_terima', '>=', $tanggalDari)
            )
            ->when(
                filled($tanggalSampai),
                fn (Builder $query) => $query->whereDate('tanggal_terima', '<=', $tanggalSampai)
            )
            ->when(
                filled($direktoratId),
                fn (Builder $query) => $query->where('direktorat_id', $direktoratId)
            )
            ->when(
                filled($sifatSuratId),
                fn (Builder $query) => $query->where('sifat_surat_id', $sifatSuratId)
            );
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $filters = $this->tableFilters ?? [];

                    return Excel::download(
                        new ReportSuratMasukExport($filters),
                        'report-surat-masuk.xlsx'
                    );
                }),
        ];
    }
}