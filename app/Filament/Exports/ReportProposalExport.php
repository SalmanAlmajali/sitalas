<?php

namespace App\Filament\Exports;

use App\Models\Proposal;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportProposalExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateTo = null,
        protected ?int $direktoratId = null,
    ) {}

    public function collection()
    {
        return $this->getQuery()->get();
    }

    protected function getQuery(): Builder
    {
        return Proposal::query()
            ->with(['unitPengolah:id,direktorat'])
            ->when(
                filled($this->dateFrom),
                fn (Builder $query) => $query->whereDate('tanggal', '>=', $this->dateFrom)
            )
            ->when(
                filled($this->dateTo),
                fn (Builder $query) => $query->whereDate('tanggal', '<=', $this->dateTo)
            )
            ->when(
                filled($this->direktoratId),
                fn (Builder $query) => $query->where('direktorat_id', $this->direktoratId)
            )
            ->orderByDesc('tanggal');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No Reg',
            'Direktorat',
            'Pengirim',
            'Perihal',
        ];
    }

    public function map($proposal): array
    {
        return [
            $proposal->tanggal ? \Carbon\Carbon::parse($proposal->tanggal)->format('d M Y') : '-',
            $proposal->no_reg,
            $proposal->unitPengolah->direktorat ?? '-',
            $proposal->pengirim,
            $proposal->perihal,
        ];
    }
}