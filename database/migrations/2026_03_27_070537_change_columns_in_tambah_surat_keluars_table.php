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
        Schema::table('tambah_surat_keluars', function (Blueprint $table) {
            // $table->dropForeign(['klasifikasi_id']);
            // $table->dropForeign(['kode_id']);
            // $table->dropForeign(['direktorat_id']);

            $table->bigInteger('klasifikasi_id', unsigned: true)
                ->nullable()
                ->change();

            $table->integer('no_urut')
                ->nullable()
                ->change();

            $table->bigInteger('kode_id', unsigned: true)
                ->nullable()
                ->change();

            $table->string('no_surat')
                ->nullable()
                ->change();

            $table->bigInteger('direktorat_id', unsigned: true)
                ->nullable()
                ->change();

            $table->foreign('klasifikasi_id')
                ->references('id')
                ->on('klasifikasis')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('kode_id')
                ->references('id')
                ->on('kode_surats')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('direktorat_id')
                ->references('id')
                ->on('unit_pengolahs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tambah_surat_keluars', function (Blueprint $table) {
            $table->dropForeign(['klasifikasi_id']);
            $table->dropForeign(['kode_id']);
            $table->dropForeign(['direktorat_id']);

            $table->bigInteger('klasifikasi_id', unsigned: true)
                ->nullable(false)
                ->change();

            $table->integer('no_urut')
                ->nullable(false)
                ->change();

            $table->bigInteger('kode_id', unsigned: true)
                ->nullable(false)
                ->change();

            $table->string('no_surat')
                ->nullable(false)
                ->change();

            $table->bigInteger('direktorat_id', unsigned: true)
                ->nullable(false)
                ->change();

            $table->foreign('klasifikasi_id')
                ->constrained('klasifikasis')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('kode_id')
                ->constrained('kode_surats')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('direktorat_id')
                ->constrained('unit_pengolahs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
