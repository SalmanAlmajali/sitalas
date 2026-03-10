<?php

namespace App\Http\Controllers;

use App\Filament\Exports\ReportSuratKeluarExport;
use App\Models\TambahSuratKeluar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportSuratKeluarController extends Controller
{
    public function print(Request $request)
    {
        $query = TambahSuratKeluar::query()
            ->with(['UnitPengolah', 'Klasifikasi', 'Kode']);

        if ($request->filled('dari_tgl')) {
            $query->whereDate('tanggal_surat', '>=', $request->dari_tgl);
        }

        if ($request->filled('sampai_tgl')) {
            $query->whereDate('tanggal_surat', '<=', $request->sampai_tgl);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%")
                    ->orWhere('kepada', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('UnitPengolah', fn ($sub) => $sub->where('direktorat', 'like', "%{$search}%"))
                    ->orWhereHas('Klasifikasi', fn ($sub) => $sub->where('klasifikasi', 'like', "%{$search}%"))
                    ->orWhereHas('Kode', fn ($sub) => $sub->where('kode', 'like', "%{$search}%"));
            });
        }

        $records = $query
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        return view('reports.surat-keluar-print', [
            'records' => $records,
            'dari_tgl' => $request->dari_tgl,
            'sampai_tgl' => $request->sampai_tgl,
            'search' => $request->search,
        ]);
    }
     public function export(Request $request)
    {
        return Excel::download(
            new ReportSuratKeluarExport(
                $request->dari_tgl,
                $request->sampai_tgl,
                $request->search
            ),
            'report-surat-keluar.xlsx'
        );
    }
}