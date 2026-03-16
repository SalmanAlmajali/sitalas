<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Klasifikasi;
use App\Models\KodeSurat;
use App\Models\SifatSurat;
use App\Models\UnitPengolah;
use App\Models\User;
class TambahSuratKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_surat',
        'klasifikasi_id',
        'no_urut',
        'kode_id',
        'no_surat',
        'sifat_surat_id',
        'perihal',
        'direktorat_id',
        'kontak_person',
        'kepada',
        'keterangan',
        'upload_file',
        'lampiran',
        'status',
        'alasan_penolakan',
        'is_requested',
        'dokumen_asli',

        'user_id',
        'is_sopd_req',
    ];
     protected $casts = [
        'tanggal_surat' => 'date',
        'is_requested' => 'boolean',
        'dokumen_asli' => 'boolean',
        'is_sopd_req' => 'boolean',
    ];

    public function Klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }
    public function Kode()
    {
        return $this->belongsTo(KodeSurat::class, 'kode_id');
    }
    public function Sifat()
    {
        return $this->belongsTo(SifatSurat::class, 'sifat_surat_id');
    }
    public function UnitPengolah()
    {
        return $this->belongsTo(UnitPengolah::class, 'direktorat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
