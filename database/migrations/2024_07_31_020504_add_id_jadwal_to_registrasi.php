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
        Schema::table('registrasis', function (Blueprint $table) {
            $table->integer('id_jadwal_dokter')->nullable();
            $table->integer('id_jadwal_khusus_dokter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrasis', function (Blueprint $table) {
            $table->dropColumn('id_jadwal_dokter');
            $table->dropColumn('id_jadwal_khusus_dokter');
        });
    }
};
