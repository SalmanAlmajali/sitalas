<?php

namespace App\Filament\Pages;

use App\Models\Penerima;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use UnitEnum;

class ReportTrackingSuratMasuk extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string |BackedEnum| null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Tracking Surat Masuk';
    protected static ?string $title = 'Tracking Surat Masuk';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.pages.report-tracking-surat-masuk';
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
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_terima')
                    ->label('Tgl Masuk')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('kodeSurat.kode')
                    ->label('Kode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kodeSurat.index')
                    ->label('Indeks')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sifatSurat.sifat_surat')
                    ->label('Sifat Surat')
                    ->badge(),

                Tables\Columns\TextColumn::make('file_upload')
                    ->label('File upload')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->url(fn ($record) => route('penerimas.file.show', ['penerima' => $record->getKey()]))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('perihal')
                    ->label('Perihal')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_by')
                    ->label('Input By')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\IconColumn::make('pengarah_terkirim')
                    ->label('Kirim Ke Pengarah')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pengarah_kirim_date')
                    ->label('Kirim Date')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pengarah_read_date')
                    ->label('Pengarah Read Date')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('pengendali_terkirim')
                    ->label('Kirim Ke Pengendali')
                    ->boolean(),

                Tables\Columns\TextColumn::make('pengendali_kirim_date')
                    ->label('Date Kirim')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pengendali_read_date')
                    ->label('Pengendali Read Date')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal_terima')
                    ->label('Filter Tanggal Terima')
                    ->form([
                        DatePicker::make('dari')->label('Dari'),
                        DatePicker::make('sampai')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('penerimas.tanggal_terima', '>=', $date)
                            )
                            ->when(
                                $data['sampai'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('penerimas.tanggal_terima', '<=', $date)
                            );
                    }),

                Tables\Filters\SelectFilter::make('direktorat_id')
                    ->label('Unit Pengolah')
                    ->relationship('unitPengolah', 'direktorat'),

                Tables\Filters\SelectFilter::make('sifat_surat_id')
                    ->label('Sifat Surat')
                    ->relationship('sifatSurat', 'sifat_surat'),

                Tables\Filters\TernaryFilter::make('pengarah_terkirim')
                    ->label('Sudah Ke Pengarah')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('pengarahs.id'),
                        false: fn (Builder $query) => $query->whereNull('pengarahs.id'),
                        blank: fn (Builder $query) => $query,
                    ),

                Tables\Filters\TernaryFilter::make('pengendali_terkirim')
                    ->label('Sudah Ke Pengendali')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('pengendalis.id'),
                        false: fn (Builder $query) => $query->whereNull('pengendalis.id'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->defaultSort('penerimas.tanggal_terima', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableQuery(): Builder
    {
        return Penerima::query()
            ->leftJoin('pengarahs', 'pengarahs.penerima_id', '=', 'penerimas.id')
            ->leftJoin('pengendalis', 'pengendalis.penerima_id', '=', 'penerimas.id')
            ->leftJoin('unit_pengolahs', 'unit_pengolahs.id', '=', 'penerimas.direktorat_id')
            ->leftJoin('kode_surats', 'kode_surats.id', '=', 'penerimas.kode_id')
            ->leftJoin('sifat_surats', 'sifat_surats.id', '=', 'penerimas.sifat_surat_id')
            ->select([
                'penerimas.*',
                'unit_pengolahs.direktorat as unit_pengolah_direktorat',
                'kode_surats.kode as kode_surat_kode',
                'kode_surats.index as kode_surat_index',
                'sifat_surats.sifat_surat as sifat_surat_sifat',

                DB::raw('CASE WHEN pengarahs.id IS NULL THEN 0 ELSE 1 END as pengarah_terkirim'),
                DB::raw('pengarahs.created_at as pengarah_kirim_date'),
                DB::raw('pengarahs.updated_at as pengarah_read_date'),

                DB::raw('CASE WHEN pengendalis.id IS NULL THEN 0 ELSE 1 END as pengendali_terkirim'),
                DB::raw('pengendalis.dikirim_pada as pengendali_kirim_date'),
                DB::raw('pengendalis.updated_at as pengendali_read_date'),
            ]);
    }
}