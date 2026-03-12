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

class ReportSuratMasuk extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Report Surat Masuk';
    protected static ?string $title = 'Report Surat Masuk';
    protected string $view = 'filament.pages.report-surat-masuk';
    protected static string|UnitEnum|null $navigationGroup = 'Report';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Penerima::query()->with([
                    'unitPengolah',
                    'kodeSurat',
                    'sifatSurat',
                    'pengarah',
                ])
            )
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
                Filter::make('tanggal_dari')
                    ->label('Dari Tanggal')
                    ->schema([
                        DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal_dari'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '>=', $date)
                        );
                    }),

                Filter::make('tanggal_sampai')
                    ->label('Sampai Tanggal')
                    ->schema([
                        DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal_sampai'] ?? null,
                            fn (Builder $query, $date): Builder => $query->whereDate('tanggal_terima', '<=', $date)
                        );
                    }),

                Filter::make('unit_pengolah')
                    ->label('Unit Pengolah')
                    ->schema([
                        Select::make('direktorat_id')
                            ->label('Unit Pengolah')
                            ->relationship('unitPengolah', 'direktorat')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Unit'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['direktorat_id'] ?? null,
                            fn (Builder $query, $state): Builder => $query->where('direktorat_id', $state)
                        );
                    }),

                Filter::make('sifat_surat')
                    ->label('Sifat Surat')
                    ->schema([
                        Select::make('sifat_surat_id')
                            ->label('Sifat Surat')
                            ->relationship('sifatSurat', 'sifat_surat')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Sifat'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['sifat_surat_id'] ?? null,
                            fn (Builder $query, $state): Builder => $query->where('sifat_surat_id', $state)
                        );
                    }),
            ])
            ->filtersFormColumns(2);
    }
}