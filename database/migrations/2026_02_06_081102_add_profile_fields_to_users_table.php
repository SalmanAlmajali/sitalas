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
            $table->unsignedBigInteger('direktorat_id')->nullable()->index()->after('remember_token');
            $table->string('file_ttd')->nullable()->after('direktorat_id');
            $table->string('no_hp', 20)->nullable()->after('file_ttd');
            $table->string('tkls')->nullable()->after('no_hp');
            $table->boolean('sopd')->default(false)->after('tkls');
            $table->timestamp('last_login')->nullable()->after('sopd');
            $table->boolean('active')->default(true)->after('last_login');
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
                'tkls',
                'sopd',
                'last_login',
                'active',
            ]);
        });
    }
};