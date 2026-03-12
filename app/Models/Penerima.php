<?php

namespace App\Models;

use App\Models\UnitPengolah;
use App\Models\KodeSurat;
use App\Models\SifatSurat;
use App\Models\Pengarah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penerima extends Model
{
    use HasFactory;

     protected $fillable = [
        'tanggal_terima',
        'tanggal_surat',
        'no_urut',
        'no_surat',
        'banyak_surat',
        'direktorat_id',
        'kode_id',
        'pengirim',
        'perihal',
        'kontak_person',
        'sifat_surat_id',
        'ringkasan_poko',
        'catatan',
        'file_upload',
        'no_box',
        'no_rak',
        'kirim_ke_pengarah_surat',
     ];
     protected $casts = [
    'tanggal_terima' => 'date',
    'tanggal_surat' => 'date',
   ];

     public function unitPengolah()
     {
        return $this->belongsTo(UnitPengolah::class, 'direktorat_id');
     }
     public function kodeSurat()
     {
        return $this->belongsTo(KodeSurat::class, 'kode_id');
     }
     public function sifatSurat()
     {
        return $this->belongsTo(SifatSurat::class, 'sifat_surat_id');
     }
     public function pengarah()
      {
         return $this->hasOne(Pengarah::class, 'penerima_id');
      }

}
