<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListBiro extends Model
{
    protected $fillable = [
        'tambah_surat_keluar_id',
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
        'tanggal_terima',
        'file_bukti_terima',
    ];

    public function tambahSuratKeluar()
    {
        return $this->belongsTo(TambahSuratKeluar::class, 'tambah_surat_keluar_id');
    }

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
}