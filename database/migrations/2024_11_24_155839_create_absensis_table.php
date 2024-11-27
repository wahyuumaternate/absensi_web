<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // ID pengguna yang hadir
        $table->date('tanggal');              // Tanggal absensi
        $table->time('jam_masuk');            // Waktu hadir
        $table->time('jam_keluar')->nullable(); // Waktu pulang (opsional)
        $table->string('status')->default('hadir'); // Status absensi (hadir/telat/dll.)
        $table->timestamps();

        // Relasi ke tabel pengguna
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

};
