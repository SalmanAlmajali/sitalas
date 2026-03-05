<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sopd_approves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tambah_surat_keluar_id')->unique()->constrained('tambah_surat_keluars')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_surat');
            $table->foreignId('klasifikasi_id')->constrained('klasifikasis')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('no_urut');
            $table->foreignId('kode_id')->constrained('kode_surats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('no_surat');
            $table->foreignId('sifat_surat_id')->constrained('sifat_surats')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('perihal');
            $table->foreignId('direktorat_id')->constrained('unit_pengolahs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('kontak_person');
            $table->string('kepada');
            $table->text('keterangan');
            $table->string('upload_file');
            $table->text('lampiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sopd_approves');
    }
};
