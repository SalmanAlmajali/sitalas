<?php

namespace App\Filament\Pages;

use App\Models\TambahSuratKeluar;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Grid;

class SopdReport extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    #protected static string |BackedEnum| null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'SOPD Report';
    protected static ?string $title = 'SOPD Report Surat Keluar';
    protected static string |UnitEnum| null $navigationGroup = 'Surat Keluar';
    protected string $view = 'filament.pages.sopd-report';

    public ?array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal_mulai' => now()->startOfMonth()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    DatePicker::make('tanggal_mulai')
                        ->label('Dari Tanggal')
                        ->native(false),

                    DatePicker::make('tanggal_selesai')
                        ->label('Sampai Tanggal')
                        ->native(false),
                ]),
            ])
            ->statePath('filters');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
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
            ->emptyStateDescription('Data akan tampil berdasarkan filter tanggal yang dipilih.');
    }

    protected function getTableQuery(): Builder
    {
        return TambahSuratKeluar::query()
            ->with([
                'unitPengolah',
                'klasifikasi',
            ])
            ->when(
                $this->filters['tanggal_mulai'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_surat', '>=', $date)
            )
            ->when(
                $this->filters['tanggal_selesai'] ?? null,
                fn (Builder $query, $date) => $query->whereDate('tanggal_surat', '<=', $date)
            );
    }

    public function getMaxContentWidth(): Width|string|null
        {
            return Width::Full;
        }
}