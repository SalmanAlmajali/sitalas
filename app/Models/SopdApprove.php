<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SopdApprove extends Model
{
    protected $guarded = [];

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
