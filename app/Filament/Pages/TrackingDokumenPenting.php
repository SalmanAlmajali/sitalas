<?php

namespace App\Filament\Pages;

use App\Models\DokumenPenting;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TrackingDokumenPenting extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.tracking-dokumen-penting';
    protected static ?string $navigationLabel = 'Laporan Dokumen Penting';
    protected static ?string $title = 'Laporan Dokumen Penting';
    protected static string|UnitEnum|null $navigationGroup = 'Report';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns([
                TextColumn::make('tgl_terima')
                    ->label('Tgl Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tgl_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('pengirim')
                    ->label('Pengirim')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('upload_file')
                    ->label('File Upload')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->perihal),

                TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable()
                    ->wrap(),

                IconColumn::make('kirim_ke_tujuan')
                    ->label('Dikirim')
                    ->boolean(),
            ])
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
            ->filtersFormColumns(1)
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Dokumen Penting')
                    ->modalContent(fn ($record) => view('filament.pages.partials.detail-dokumen-penting', [
                        'record' => $record,
                    ])),
            ])
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Belum ada data dokumen penting')
            ->emptyStateDescription('Pilih rentang tanggal dari tombol filter lalu klik Apply filters.');
    }

    protected function getTableQuery(): Builder
    {
        $tanggalDari = data_get($this->tableFilters, 'tanggal.tanggal_dari');
        $tanggalSampai = data_get($this->tableFilters, 'tanggal.tanggal_sampai');

        $query = DokumenPenting::query()
            ->with('unitPengolah');

        if (blank($tanggalDari) || blank($tanggalSampai)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereDate('tgl_terima', '>=', $tanggalDari)
            ->whereDate('tgl_terima', '<=', $tanggalSampai)
            ->latest('tgl_terima');
    }
}