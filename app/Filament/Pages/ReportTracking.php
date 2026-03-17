<?php

namespace App\Filament\Pages;

use App\Models\SuratMasuk;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ReportTracking extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected string $view = 'filament.pages.report-tracking';
    protected static ?string $navigationLabel = 'Report Tracking';
    protected static ?string $title = 'Report Tracking Surat Masuk';
    protected static string | UnitEnum | null $navigationGroup = 'Report';

    public static function canAccess(): bool
    {
        return auth()->user()?->isStaf();
    }
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isStaf();
    }


    public function table(Table $table): Table
    {
        return $table

            ->query(function () {

                $tanggalDari = $this->tableFilters['tanggal']['tanggal_dari'] ?? null;
                $tanggalSampai = $this->tableFilters['tanggal']['tanggal_sampai'] ?? null;
                $direktorat = $this->tableFilters['direktorat_id']['value'] ?? null;

                $query = SuratMasuk::query();

                // Jika semua filter kosong, jangan tampilkan data
                if (!$tanggalDari && !$tanggalSampai && !$direktorat) {
                    $query->whereRaw('1 = 0');
                }

                return $query;
            })

            ->columns([

                Tables\Columns\TextColumn::make('tanggal_terima')
                    ->date()
                    ->label('Tanggal Masuk'),

                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->date()
                    ->label('Tanggal Surat'),

                Tables\Columns\TextColumn::make('no_urut')
                    ->label('No Urut')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pengirim')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unitPengolah.direktorat')
                    ->label('Direktorat'),

                Tables\Columns\TextColumn::make('upload_file')
                    ->label('File Upload')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->url(fn ($record) => route('reporttrackings.file.show', [
                        'reportTracking' => $record->getKey()
                    ]))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('kodeSurat.kode')
                    ->label('Kode Surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kodeSurat.index')
                    ->label('Indeks')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sifatSurat.sifat_surat')
                    ->label('Sifat Surat'),

                Tables\Columns\TextColumn::make('perihal')
                    ->limit(40),

                Tables\Columns\TextColumn::make('no_surat')
                    ->label('Nomor Surat')
                    ->searchable(),

            ])

            ->filters([

                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Tanggal Dari'),

                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Tanggal Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn ($query) => $query->whereDate('tanggal_terima', '>=', $data['tanggal_dari'])
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn ($query) => $query->whereDate('tanggal_terima', '<=', $data['tanggal_sampai'])
                            );
                    }),

                Tables\Filters\SelectFilter::make('direktorat_id')
                    ->relationship('unitPengolah', 'direktorat')
                    ->label('Direktorat'),

            ])

            ->defaultSort('tanggal_terima', 'desc')

            ->emptyStateHeading('Silakan gunakan filter terlebih dahulu')
            ->emptyStateDescription('Data report tracking akan muncul setelah memilih filter tanggal atau direktorat.');
    }
}