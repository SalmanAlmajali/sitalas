<?php

namespace App\Filament\Pages;

use App\Models\TambahSuratKeluar;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Actions\Action;

class ReportSuratKeluar extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Report Surat Keluar';
    protected static ?string $title = 'Report Surat Keluar';
    protected static string | UnitEnum | null $navigationGroup = 'Report';
    protected string $view = 'filament.pages.report-surat-keluar';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'dari_tgl' => null,
            'sampai_tgl' => null,
            'search' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('dari_tgl')
                    ->label('Dari Tanggal')
                    ->native(false)
                    ->live(),

                DatePicker::make('sampai_tgl')
                    ->label('Sampai Tanggal')
                    ->native(false)
                    ->live(),

                TextInput::make('search')
                    ->label('Search')
                    ->placeholder('Cari no surat, perihal, kepada, unit pengolah')
                    ->live(debounce: 500),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('tanggal_surat')
                    ->label('Tgl Surat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('UnitPengolah.direktorat')
                    ->label('Unit Pengolah')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('Kode.kode')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->wrap()
                    ->limit(50),

                TextColumn::make('kepada')
                    ->label('Kepada')
                    ->searchable()
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
            ->headerActions([
                Action::make('reset_filter')
                    ->label('Reset Filter')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function () {
                        $this->form->fill([
                            'dari_tgl' => null,
                            'sampai_tgl' => null,
                            'search' => null,
                        ]);
                    }),

                Action::make('print_all')
                    ->label('Print Semua Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn () => route('report.surat-keluar.print', [
                        'dari_tgl' => $this->data['dari_tgl'] ?? null,
                        'sampai_tgl' => $this->data['sampai_tgl'] ?? null,
                        'search' => $this->data['search'] ?? null,
                    ]))
                    ->openUrlInNewTab(),

                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn () => route('report.surat-keluar.export', [
                        'dari_tgl' => $this->data['dari_tgl'] ?? null,
                        'sampai_tgl' => $this->data['sampai_tgl'] ?? null,
                        'search' => $this->data['search'] ?? null,
                    ]))
                    ->openUrlInNewTab(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return TambahSuratKeluar::query()
            ->with(['UnitPengolah', 'Klasifikasi', 'Kode'])
            ->when(
                filled($this->data['dari_tgl'] ?? null),
                fn (Builder $query) => $query->whereDate('tanggal_surat', '>=', $this->data['dari_tgl'])
            )
            ->when(
                filled($this->data['sampai_tgl'] ?? null),
                fn (Builder $query) => $query->whereDate('tanggal_surat', '<=', $this->data['sampai_tgl'])
            )
            ->when(
                filled($this->data['search'] ?? null),
                function (Builder $query) {
                    $search = $this->data['search'];

                    $query->where(function (Builder $q) use ($search) {
                        $q->where('no_surat', 'like', "%{$search}%")
                            ->orWhere('perihal', 'like', "%{$search}%")
                            ->orWhere('kepada', 'like', "%{$search}%")
                            ->orWhere('keterangan', 'like', "%{$search}%")
                            ->orWhereHas('UnitPengolah', fn (Builder $sub) => $sub->where('direktorat', 'like', "%{$search}%"))
                            ->orWhereHas('Klasifikasi', fn (Builder $sub) => $sub->where('klasifikasi', 'like', "%{$search}%"))
                            ->orWhereHas('Kode', fn (Builder $sub) => $sub->where('kode', 'like', "%{$search}%"));
                    });
                }
            );
    }
}