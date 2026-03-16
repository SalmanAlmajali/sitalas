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
        Schema::table('list_biros', function (Blueprint $table) {
            $table->date('tanggal_terima')->nullable();
            $table->string('file_bukti_terima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_biros', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_terima',
                'file_bukti_terima',
            ]);
        });
    }
};
