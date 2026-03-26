<?php

namespace App\Filament\Pages;

use App\Models\TambahSuratKeluar;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\EditAction;

class SopdReport extends Page implements HasTable
{
    use InteractsWithTable;

    #protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Sopd Report';
    protected static ?string $title = 'SOPD Report Surat Keluar';
    protected static string | UnitEnum | null $navigationGroup = 'Surat Keluar';
    protected string $view = 'filament.pages.sopd-report';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isUser();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isUser();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('no_urut')
                    ->label('No Urt')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('perihal')
                    ->label('Perihal')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('kepada')
                    ->label('Nama')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kontak_person')
                    ->label('No HP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('klasifikasi.nama_klasifikasi')
                    ->label('Klasifikasi')
                    ->wrap(),

                Tables\Columns\IconColumn::make('upload_file')
                    ->label('File Upload')
                    ->boolean(fn ($state) => filled($state)),

                Tables\Columns\IconColumn::make('dokumen_asli')
                    ->label('Dokumen Asli')
                    ->boolean(),
            ])
            ->defaultSort('tanggal_surat', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
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
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Edit Dokumen Asli')
                    ->modalDescription('Aktifkan atau nonaktifkan status dokumen asli.')
                    ->form([
                        Toggle::make('dokumen_asli')
                            ->label('Dokumen Asli')
                            ->inline(false)
                            ->required(),
                    ])
                    ->using(function (TambahSuratKeluar $record, array $data): TambahSuratKeluar {
                        $record->update([
                            'dokumen_asli' => $data['dokumen_asli'],
                        ]);

                        return $record;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Dokumen asli berhasil diperbarui')
                    ),
            ])
            ->emptyStateHeading('Belum ada data surat keluar')
            ->emptyStateDescription('Pilih rentang tanggal dari tombol filter lalu klik Apply filters.');
    }

    protected function getTableQuery(): Builder
    {
        $tanggalDari = data_get($this->tableFilters, 'tanggal.tanggal_dari');
        $tanggalSampai = data_get($this->tableFilters, 'tanggal.tanggal_sampai');

        $query = TambahSuratKeluar::query()
            ->with([
                'unitPengolah',
                'klasifikasi',
            ]);

        if (blank($tanggalDari) || blank($tanggalSampai)) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereDate('tanggal_surat', '>=', $tanggalDari)
            ->whereDate('tanggal_surat', '<=', $tanggalSampai);
    }

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}