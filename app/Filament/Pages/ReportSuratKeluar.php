<?php

namespace App\Filament\Pages;

use App\Models\TambahSuratKeluar;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ReportSuratKeluar extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Report Surat Keluar';
    protected static ?string $title = 'Report Surat Keluar';
    protected static string | UnitEnum | null $navigationGroup = 'Report';
    protected string $view = 'filament.pages.report-surat-keluar';

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
                TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('UnitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->wrap(),

                TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->wrap(),

                TextColumn::make('Kode.kode')
                    ->label('Kode'),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->wrap()
                    ->limit(50),

                TextColumn::make('kepada')
                    ->label('Kepada')
                    ->wrap(),

                TextColumn::make('Klasifikasi.klasifikasi')
                    ->label('Klasifikasi')
                    ->wrap(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->wrap()
                    ->limit(40),

                TextColumn::make('lampiran')
                    ->label('Lampiran')
                    ->wrap()
                    ->limit(30),
            ])
            ->defaultSort('tanggal_surat', 'desc')
            ->paginated([10, 25, 50, 100])
            ->searchable(false)
            ->filters([
                Filter::make('tanggal')
                    ->label('Filter Tanggal')
                    ->schema([
                        DatePicker::make('dari_tgl')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),

                        DatePicker::make('sampai_tgl')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),
                    ])
                    ->columns(2),
            ])
            ->filtersFormColumns(1)
            ->headerActions([
                Action::make('print_all')
                    ->label('Print Semua Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn () => route('report.surat-keluar.print', [
                        'dari_tgl' => data_get($this->tableFilters, 'tanggal.dari_tgl'),
                        'sampai_tgl' => data_get($this->tableFilters, 'tanggal.sampai_tgl'),
                    ]))
                    ->openUrlInNewTab(),

                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn () => route('report.surat-keluar.export', [
                        'dari_tgl' => data_get($this->tableFilters, 'tanggal.dari_tgl'),
                        'sampai_tgl' => data_get($this->tableFilters, 'tanggal.sampai_tgl'),
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Belum ada data surat keluar')
            ->emptyStateDescription('Pilih rentang tanggal dari tombol filter lalu klik Apply filters.');
    }

    protected function getTableQuery(): Builder
    {
        $tanggalDari = data_get($this->tableFilters, 'tanggal.dari_tgl');
        $tanggalSampai = data_get($this->tableFilters, 'tanggal.sampai_tgl');

        $query = TambahSuratKeluar::query()
            ->with(['UnitPengolah', 'Klasifikasi', 'Kode']);

        if (blank($tanggalDari) || blank($tanggalSampai)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereDate('tanggal_surat', '>=', $tanggalDari)
            ->whereDate('tanggal_surat', '<=', $tanggalSampai);
    }
}