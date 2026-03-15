<?php

namespace App\Http\Controllers;

use App\Models\Penerima;
use App\Models\Pengarah;
use App\Models\Pengendali;
use App\Models\TambahSuratKeluar;
use App\Models\SopdApprove;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function penerima(Penerima $penerima)
    {
        return $this->serveLocalFile($penerima->file_upload);
    }

    public function pengarah(Pengarah $pengarah)
    {
        return $this->serveLocalFile($pengarah->file_upload);
    }
    public function pengendali(Pengendali $pengendali)
    {
        return $this->serveLocalFile($pengendali->file_upload);
    }

    public function tambahSuratKeluar(TambahSuratKeluar $tambahSuratKeluar)
    {
        return $this->serveLocalFile($tambahSuratKeluar->upload_file);
    }
    public function sopdApprove(SopdApprove $sopdApprove)
    {
        return $this->serveLocalFile($sopdApprove->upload_file);
    }
    public function reportTracking(SuratMasuk $reportTracking)
    {
        return $this->serveLocalFile($reportTracking->upload_file);
    }

    private function serveLocalFile(string $path)
    {
        $disk = 'local';

        abort_unless(Storage::disk($disk)->exists($path), 404);

        $absolutePath = Storage::disk($disk)->path($path);
        $downloadName = basename($path);

        return response()->file($absolutePath, [
            'Content-Disposition' => 'inline; filename="'.$downloadName.'"',
        ]);
    }
}