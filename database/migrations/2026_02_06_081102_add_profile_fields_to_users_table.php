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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('direktorat_id')->nullable()->index();
            $table->string('file_ttd')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('type')->default("user");
            $table->string('tkls')->nullable();
            $table->boolean('sopd')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'direktorat_id',
                'file_ttd',
                'no_hp',
                'type',
                'tkls',
                'sopd',
                'last_login',
                'active',
            ]);
        });
    }
};