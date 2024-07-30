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
        Schema::create('registrasis', function (Blueprint $table) {
            $table->id('id_registrasi');
            $table->unsignedBigInteger('id_pasien');
            $table->date('tanggal_kunjungan');
            $table->unsignedBigInteger('id_poliklinik');
            $table->unsignedBigInteger('id_dokter');
            $table->string('kode_booking')->unique();
            $table->unsignedInteger('nomor_urut')->default(1)->nullable();
            $table->time('jam_mulai')->nullable(); 
            $table->time('jam_selesai')->nullable(); 
            $table->enum('status', ['Pending', 'Confirmed', 'Canceled'])->default('Pending');
            $table->timestamps();

            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_poliklinik')->references('id_poliklinik')->on('polikliniks')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasis');
    }
};
