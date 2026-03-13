<?php

namespace App\Http\Controllers;

use App\Filament\Exports\ReportProposalExport;
use App\Models\Proposal;
use App\Models\UnitPengolah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProposalPrintController extends Controller
{
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $direktoratId = $request->get('direktorat_id');

        $hasFilter =
            filled($dateFrom) ||
            filled($dateTo) ||
            filled($direktoratId);

        if (! $hasFilter) {
            return back()->with('error', 'Pilih minimal satu filter terlebih dahulu.');
        }

        return Excel::download(
            new ReportProposalExport(
                dateFrom: $dateFrom,
                dateTo: $dateTo,
                direktoratId: filled($direktoratId) ? (int) $direktoratId : null,
            ),
            'report-proposal.xlsx'
        );
    }

    public function print(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $direktoratId = $request->get('direktorat_id');

        $hasFilter =
            filled($dateFrom) ||
            filled($dateTo) ||
            filled($direktoratId);

        $query = Proposal::query()
            ->with(['unitPengolah:id,direktorat'])
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
            )
            ->orderByDesc('tanggal');

        $proposals = $hasFilter ? $query->get() : collect();

        $direktorat = filled($direktoratId)
            ? UnitPengolah::query()->find($direktoratId)?->direktorat
            : null;

        return view('print.proposal-print', [
            'proposals' => $proposals,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'direktorat' => $direktorat,
        ]);
    }
}