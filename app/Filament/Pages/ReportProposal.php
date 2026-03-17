<?php

namespace App\Filament\Pages;

use App\Models\Proposal;
use App\Models\UnitPengolah;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ReportProposal extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string | UnitEnum | null $navigationGroup = 'Proposal';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Report Proposal';
    protected static ?string $title = 'Report Proposal';

    protected string $view = 'filament.pages.report-proposal';

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
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tgl')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_reg')
                    ->label('No Reg')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unitPengolah.direktorat')
                    ->label('Direktorat')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('pengirim')
                    ->label('Pengirim')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable()
                    ->wrap(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->filters([
                Filter::make('tanggal')
                    ->label('Filter Tanggal')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),

                        DatePicker::make('date_to')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('Pilih tanggal'),
                    ])
                    ->columns(2),

                Filter::make('direktorat')
                    ->label('Direktorat')
                    ->schema([
                        Select::make('direktorat_id')
                            ->label('Direktorat')
                            ->options(fn () => UnitPengolah::query()->pluck('direktorat', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Direktorat'),
                    ]),
            ])
            ->filtersFormColumns(1)
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn () => route('report.proposal.export', [
                        'date_from' => data_get($this->tableFilters, 'tanggal.date_from'),
                        'date_to' => data_get($this->tableFilters, 'tanggal.date_to'),
                        'direktorat_id' => data_get($this->tableFilters, 'direktorat.direktorat_id'),
                    ]))
                    ->openUrlInNewTab(),

                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn () => route('report.proposal.print', [
                        'date_from' => data_get($this->tableFilters, 'tanggal.date_from'),
                        'date_to' => data_get($this->tableFilters, 'tanggal.date_to'),
                        'direktorat_id' => data_get($this->tableFilters, 'direktorat.direktorat_id'),
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Pilih minimal satu filter dari tanggal atau direktorat lalu klik Apply filters.');
    }

    protected function getTableQuery(): Builder
    {
        $dateFrom = data_get($this->tableFilters, 'tanggal.date_from');
        $dateTo = data_get($this->tableFilters, 'tanggal.date_to');
        $direktoratId = data_get($this->tableFilters, 'direktorat.direktorat_id');

        $query = Proposal::query()
            ->with(['unitPengolah:id,direktorat']);

        $hasFilter =
            filled($dateFrom) ||
            filled($dateTo) ||
            filled($direktoratId);

        if (! $hasFilter) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->when(
                filled($dateFrom),
                fn (Builder $query) => $query->whereDate('tanggal', '>=', $dateFrom)
            )
            ->when(
                filled($dateTo),
                fn (Builder $query) => $query->whereDate('tanggal', '<=', $dateTo)
            )
            ->when(
                filled($direktoratId),
                fn (Builder $query) => $query->where('direktorat_id', $direktoratId)
            );
    }
}